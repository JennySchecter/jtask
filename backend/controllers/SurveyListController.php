<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AuditLog;
use backend\models\Channel;
use backend\models\Department;
use backend\models\Fba;
use backend\models\InvestigateType;
use backend\models\User;
use backend\models\Waybill;
use Yii;
use backend\models\SurveyList;
use backend\models\SurveyListSearch;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \PHPExcel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * SurveyListController implements the CRUD actions for SurveyList model.
 */
class SurveyListController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SurveyList models.
     * @return mixed
     */
//    public function actionIndex()
//    {
//        $searchModel = new SurveyListSearch();
//        $query = $searchModel->search(Yii::$app->request->queryParams);
//
//        var_dump($query->count());die;
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//        ]);
//    }

    public function actionIndex()
    {
        //$this->layout = 'main';

        $searchModel = new SurveyListSearch();
        $query = $searchModel->search(Yii::$app->request->queryParams);
        $count = $query->count();
        $pager  =new Pagination([
            'totalCount'=>$count,
            'pageSize'=>20,
        ]);
        $lists = $query->offset($pager->offset)->limit($pager->limit)->all();
        return $this->render('index',[
            'lists'=>$lists,
            'pager'=>$pager,
            'searchModel'=>$searchModel
        ]);
    }
    
    /*
     * 根据订单号获取用户名、调查渠道名
     */
    public function actionGetname()
    {
        $onum = Yii::$app->request->get('onum');

        $waybill = Waybill::find()->where(['orderNum' => $onum])->one();
        if(!empty($waybill) && !empty($onum)){
            $channelParent = Channel::find()->where(['id'=>$waybill['channelParentId']])->one();
            $channelChild = Channel::find()->where(['id'=>$waybill['channelChildId']])->one();
            if(empty($channelParent) && empty($channelChild)){
                $dc_channel = '暂无';
            }elseif(!empty($channelParent) && empty($channelChild)){
                $dc_channel = $channelParent['name'].'(父渠道)';
            }elseif(!empty($channelParent) && !empty($channelChild)){
                $dc_channel = $channelParent['name'].'(父渠道)-'.$channelChild['name'].'(子渠道)';
            }else{  //不可能只有子渠道而没有父渠道
                $dc_channel = 'error';
            }
            return json_encode(['errorCode'=>0,'memberName'=>$waybill['memberName'],'dc_channel'=>$dc_channel]);
        }else{
            return json_encode(['errorCode'=>1,'memberName'=>'订单号不存在']);
        }
    }

    /*
     * 处理工单
     */
    public function actionDealwith($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $post['SurveyList']['next_time'] = strtotime($post['SurveyList']['repay_time']);
            if($model->dealwith($post,$id)){
                $this->redirect(['view','id'=>$id]);
            }
        }

        return $this->render('dealwith', [
            'model' => $model,
        ]);
    }

    //处理中的工单追加情况
    public function actionAppend($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->request->isPost){
            if($model->append(Yii::$app->request->post(),$id)){
                return $this->redirect(['index']);
            }
        }
        return $this->render('append', [
            'model' => $model,
        ]);
    }
    //提交预归档理赔信息
    public function actionBeforefile($id)
    {
        $model = $this->findModel($id);
        return $this->render('beforefile', [
            'model' => $model,
        ]);
    }

    //处理理赔信息
    public function actionCompensate()
    {
        //var_dump(Yii::$app->request->post());die;
        //数据判断
        $post = Yii::$app->request->post();
        //理赔 必须选择官方或公司至少一个
        if($post['dc_result'] == 2 && empty($post['pc_type'])){
            return '请至少选择一个理赔方';
        }
        //理赔相关信息填写
        if($post['dc_result'] == 2 && in_array('1',$post['pc_type'])){
            if(empty($post['office_name'])){
                return '请填写官方承担姓名';
            }
            if($post['office_money'] <= 0){
                return '理赔金额不得小于0';
            }
        }
        if($post['dc_result'] == 2 && in_array('2',$post['pc_type'])){
            if(empty($post['departments'])){
                return '请选择承担部门';
            }
            if(empty($post['company_name'])){
                return '请选择承担员工';
            }
            if($post['company_money'] <= 0){
                return '理赔金额不得小于0';
            }
            if(count($post['departments'])==1 && count($post['company_name'])>2){
                return '1部门不能超过2个员工';
            }
        }
        $model = new SurveyList();
        if($model->compensate($post)){
            return $this->render('view', [
                'model' => $this->findModel($post['s_id']),
            ]);
        }else{
            Yii::$app->session->setFlash('msg','操作失败');
            $model = $this->findModel($post['s_id']);
            return $this->render('beforefile', [
                'model' => $model,
            ]);
        }
    }

    /**填写归档意见归档
      *
     */
    public function actionFile()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $model = SurveyList::find()->where(['id'=>$post['id']])->one();
            $admin = Admin::findIdentity(Yii::$app->user->getId());
            $model->status = 2;
            $model->file_content = $post['file_content'];
            $model->file_user = $admin['username'];
            $model->file_time = time();

            if($model->save(false)){
                Yii::$app->session->setFlash('msg','归档成功！');
                return $this->redirect(['index']);
            }
        }else{
            $id = Yii::$app->request->get('id');
            return $this->render('file',['id'=>$id]);
        }
    }
    
    //撤销预归档
    public function actionRevoke($id)
    {
        $model = $this->findModel($id);
        $model->status = 1;
        $model->dc_result = null;
        $model->pc_result = null;
        $model->office_money = null;
        $model->office_name = null;
        $model->company_money = null;
        $model->departmentIds = null;
        $model->staffIds = null;
        if($model->save(false)){
            return $this->redirect(['index']);
        }
    }
    /**
     * Displays a single SurveyList model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SurveyList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SurveyList();

        if (Yii::$app->request->isPost && $model->create(Yii::$app->request->post())) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /*
     * 自动提取运单信息生成调查工单信息
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionAutoCreate($id)
    {
        $model = new SurveyList();
        $waybillModel = Waybill::find()->where(['id'=>$id])->one();
        if(!empty($waybillModel)){
            $channelParent = Channel::find()->where(['id'=>$waybillModel['channelParentId']])->one();
            $channelChild = Channel::find()->where(['id'=>$waybillModel['channelChildId']])->one();
            if(empty($channelParent) && empty($channelChild)){
                $dc_channel = '暂无';
            }elseif(!empty($channelParent) && empty($channelChild)){
                $dc_channel = $channelParent['name'].'(父渠道)';
            }elseif(!empty($channelParent) && !empty($channelChild)){
                $dc_channel = $channelParent['name'].'(父渠道)-'.$channelChild['name'].'(子渠道)';
            }else{  //不可能只有子渠道而没有父渠道
                $dc_channel = 'error';
            }
        }
        if (Yii::$app->request->isPost && $model->create(Yii::$app->request->post())) {
            return $this->redirect(['index']);
        }

        return $this->render('auto-create', [
            'waybillModel' => $waybillModel,
            'model' => $model,
            'dc_channel'=>$dc_channel
        ]);
    }

    /*
     * 自动提取Fba运单信息生成调查工单信息
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionAutoCreateByFba($id)
    {
        $model = new SurveyList();
        $fbaModel = Fba::find()->where(['id'=>$id])->one();
        if(!empty($fbaModel)){
            $channelParent = Channel::find()->where(['id'=>$fbaModel['channelParentId']])->one();
            $channelChild = Channel::find()->where(['id'=>$fbaModel['channelChildId']])->one();
            if(empty($channelParent) && empty($channelChild)){
                $dc_channel = '暂无';
            }elseif(!empty($channelParent) && empty($channelChild)){
                $dc_channel = $channelParent['name'].'(父渠道)';
            }elseif(!empty($channelParent) && !empty($channelChild)){
                $dc_channel = $channelParent['name'].'(父渠道)-'.$channelChild['name'].'(子渠道)';
            }else{  //不可能只有子渠道而没有父渠道
                $dc_channel = 'error';
            }
        }
        if (Yii::$app->request->isPost && $model->create(Yii::$app->request->post())) {
            return $this->redirect(['index']);
        }

        return $this->render('auto-create-by-fba', [
            'fbaModel' => $fbaModel,
            'model' => $model,
            'dc_channel'=>$dc_channel
        ]);
    }
    /*
     * 渠道下拉列表检索
     * @param $q
     * @return array
     */
    public function actionSearchChannel($q)
    {
        //输出json格式
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '','text' => '']];
        if(!$q){
            return $out;
        }
        $data = Channel::find()
                ->select('id,name as text')
                ->andFilterWhere(['like','name',$q])
                ->limit(50)
                ->asArray()
                ->all();
        $out['results'] = array_values($data);
        return $out;
    }
    /*
     * 渠道下拉列表检索
     * @param $q
     * @return array
     */
    public function actionSearchUser($q)
    {
        //输出json格式
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '','text' => '']];
        if(!$q){
            return $out;
        }
        $data = User::find()
            ->select('name as id,name as text')
            ->andFilterWhere(['like','name',$q])
            ->limit(50)
            ->asArray()
            ->all();
        $out['results'] = array_values($data);
        return $out;
    }

    /*
     * 部门下拉列表检索
     * @param $q
     * @return array
     */
    public function actionSearchDepartment($q)
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if(!$q){
            return $out;
        }
        $data = Department::find()
                ->select('id,name as text')
                ->andFilterWhere(['like','name',$q])
                ->limit(50)
                ->asArray()
                ->all();
        $out['results'] = array_values($data);
        return $out;
    }

    /*
     * 员工下拉列表检索
     * @param $q
     * @return array
     */
    public function actionSearchStaff($q)
    {
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if(!$q){
            return $out;
        }
        $data = Admin::find()
            ->select('id,nickName as text')
            ->andFilterWhere(['like','nickName',$q])
            ->limit(50)
            ->asArray()
            ->all();
        $out['results'] = array_values($data);
        return $out;
    }
    /**
     * Updates an existing SurveyList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /*
     * Deletes an existing SurveyList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SurveyList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SurveyList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SurveyList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //导出异常件调查工单
    public function actionExport()
    {
        $searchModel = new SurveyListSearch();
        $searchData = $searchModel->search(Yii::$app->request->queryParams)->all();
        var_dump(Yii::$app->request->queryParams);die;
        $objPHPExcel = new PHPExcel();

        try{
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1','新建日期')
                        ->setCellValue('B1','订单编号')
                        ->setCellValue('C1','客户名')
                        ->setCellValue('D1','调查渠道')
                        ->setCellValue('E1','调查类型')
                        ->setCellValue('F1','调查结果')
                        ->setCellValue('G1','承担方(官方/公司)')
                        ->setCellValue('H1','官方名称')
                        ->setCellValue('I1','官方承担金额')
                        ->setCellValue('J1','公司承担金额')
                        ->setCellValue('K1','公司部门')
                        ->setCellValue('L1','员工')
                        ->setCellValue('M1','归档人')
                        ->setCellValue('N1','归档日期');

            $n = 2;
            $dc_type = InvestigateType::dropDrownList();
            foreach ($searchData as $v){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.($n),date('Y-m-d H:i:s',$v['c_time']));
                $objPHPExcel->getActiveSheet()->setCellValue('B'.($n),$v['order_num']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.($n),$v['member_name']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.($n),$v['dc_channel']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.($n),$dc_type[$v['it_id']]);
                if($v['dc_result']==1){
                    $result = '赔偿';
                }elseif($v['dc_result']==2){
                    $result = '道歉';
                }else{
                    $result = '';
                }
                $objPHPExcel->getActiveSheet()->setCellValue('F'.($n),$result);
                if($v['pc_result']==1){
                    $undertake = '官方';
                }elseif($v['pc_result']==2){
                    $undertake = '公司';
                }elseif($v['pc_result']==3){
                    $undertake = '共同承担';
                }else{
                    $undertake = '';
                }
                $objPHPExcel->getActiveSheet()->setCellValue('G'.($n),$undertake);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.($n),$v['office_name']);
                $objPHPExcel->getActiveSheet()->setCellValue('I'.($n),$v['office_money']);
                $objPHPExcel->getActiveSheet()->setCellValue('J'.($n),$v['company_money']);
                $departmentStr = '';
                if($v['departmentIds']){
                    $ids = explode(';',$v['departmentIds']);
                    $department = Department::find()->where(['in','id',$ids])->asArray()->all();
                    foreach ($department as $dv){
                        $departmentStr .= $dv['name'] . ';';
                    }
                }
                $staffStr = '';
                if($v['staffIds']){
                    $ids = explode(';',$v['staffIds']);
                    $staff = Admin::find()->where(['in','id',$ids])->asArray()->all();
                    foreach ($staff as $sv){
                        $staffStr .= $sv['name'] . ';';
                    }
                }
                $objPHPExcel->getActiveSheet()->setCellValue('K'.($n),$departmentStr);
                $objPHPExcel->getActiveSheet()->setCellValue('L'.($n),$v['office_money']);
                $objPHPExcel->getActiveSheet()->setCellValue('M'.($n),$staffStr);
                $objPHPExcel->getActiveSheet()->setCellValue('N'.($n),date('Y-m-d H:i:s',$v['file_time']));
                $n++;
            }

            //单元格居中
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            ob_start();
            header('Content-Type:application/vnd.ms-excel');
            //设置输出文件名及格式
            header('Content-Disposition:attachment;filename="'.'异常件调查工单-'.date("YmdHis").'.xls"');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            $objWriter->save('php://output');
            ob_flush();
        }catch (\Exception $e){
            echo $e;exit;
        }
    }

    public function actionExportone()
    {
        $searchModel = new SurveyListSearch();
        $searchData = $searchModel->search(Yii::$app->request->queryParams)->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        header('Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $filename = 'test.xls';
        header('Content-Disposition:attachment;filename="'.$filename.'"');
        header('Cache-Control:max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }

    /**
     * 查看异常件调查工单的审核进度 2018/12/23
     */
    public function actionCheckProgress()
    {
        $id = Yii::$app->request->get('sid');
        $auditModel = AuditLog::find()->where([ 'sid' => $id ])->orderBy('time desc')->asArray()->all();

        return $this->render('check-progress',[
            'auditLists' => $auditModel,
        ]);
    }

    /**
     * 客户经理审核 2019/01/06
     */
    public function actionAccountManagerAudit()
    {
        $id = Yii::$app->request->get('id');
        $survey = SurveyList::find()->where([ 'id' => $id ])->one();

        if(Yii::$app->request->isPost){

            $post = Yii::$app->request->post();
            $survey = SurveyList::find()->where([ 'id' => $post['id'] ])->one();
            if($survey['audit'] != 0){
                Yii::$app->session->setFlash('error','非法状态');
            }else{
                $data['sid'] = $post['id'];
                $data['flag'] = $post['audit'];
                $data['operator_id'] = Yii::$app->user->getId();
                $data['operator_name'] = Yii::$app->user->getIdentity()->username;
                $data['desc'] = $post['audit']==0 ? '客服经理审核未通过':'客服经理审核通过';
                $data['time'] = time();

                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $survey->audit = ($post['audit'])==0 ? 10:11;
                    $res1 = $survey->save(false);
                    $res2 = AuditLog::addLog($data);

                    if($res1 && $res2){
                        $transaction->commit();
                        yii::$app->session->setFlash('success','审核成功');
                    }else{
                        $transaction->rollBack();
                        yii::$app->session->setFlash('error','审核失败');
                    }
                }catch (\Exception $e){
                    $transaction->rollBack();
                    yii::$app->session->setFlash('error','审核失败');
                }
            }
        }
        return $this->render('audit-one',[
            'model' => $survey,
        ]);
    }

    /**
     * 财务经理审核 2019/01/14
     */
    public function actionFinanceManagerAudit()
    {
        $id = Yii::$app->request->get('id');
        $survey = SurveyList::find()->where([ 'id' => $id ])->one();

        if(Yii::$app->request->isPost){

            $post = Yii::$app->request->post();
            $survey = SurveyList::find()->where([ 'id' => $post['id'] ])->one();
            if($survey['audit'] != 11){
                Yii::$app->session->setFlash('error','非法状态');
            }else{
                $data['sid'] = $post['id'];
                $data['flag'] = $post['audit'];
                $data['operator_id'] = Yii::$app->user->getId();
                $data['operator_name'] = Yii::$app->user->getIdentity()->username;
                $data['desc'] = $post['audit']==0 ? '财务经理审核未通过':'财务经理审核通过';
                $data['time'] = time();

                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $survey->audit = ($post['audit'])==0 ? 20:21;
                    $res1 = $survey->save(false);
                    $res2 = AuditLog::addLog($data);

                    if($res1 && $res2){
                        $transaction->commit();
                        yii::$app->session->setFlash('success','审核成功');
                    }else{
                        $transaction->rollBack();
                        yii::$app->session->setFlash('error','审核失败');
                    }
                }catch (\Exception $e){
                    $transaction->rollBack();
                    yii::$app->session->setFlash('error','审核失败');
                }
            }
        }
        return $this->render('audit-one',[
            'model' => $survey,
        ]);
    }

    /**
     * 分管副总审核 2019/01/14
     */
    public function actionDeputyManagerAudit()
    {
        $id = Yii::$app->request->get('id');
        $survey = SurveyList::find()->where([ 'id' => $id ])->one();

        if(Yii::$app->request->isPost){

            $post = Yii::$app->request->post();
            $survey = SurveyList::find()->where([ 'id' => $post['id'] ])->one();
            if($survey['audit'] != 21){
                Yii::$app->session->setFlash('error','非法状态');
            }else{
                $data['sid'] = $post['id'];
                $data['flag'] = $post['audit'];
                $data['operator_id'] = Yii::$app->user->getId();
                $data['operator_name'] = Yii::$app->user->getIdentity()->username;
                $data['desc'] = $post['audit']==0 ? '分管副总审核未通过':'分管副总审核通过';
                $data['time'] = time();

                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $survey->audit = ($post['audit'])==0 ? 30:31;
                    $res1 = $survey->save(false);
                    $res2 = AuditLog::addLog($data);

                    if($res1 && $res2){
                        $transaction->commit();
                        yii::$app->session->setFlash('success','审核成功');
                    }else{
                        $transaction->rollBack();
                        yii::$app->session->setFlash('error','审核失败');
                    }
                }catch (\Exception $e){
                    $transaction->rollBack();
                    yii::$app->session->setFlash('error','审核失败');
                }
            }
        }
        return $this->render('audit-one',[
            'model' => $survey,
        ]);
    }

    /**
     * 总经理审核 2019/01/14
     */
    public function actionGeneralManagerAudit()
    {
        $id = Yii::$app->request->get('id');
        $survey = SurveyList::find()->where([ 'id' => $id ])->one();

        if(Yii::$app->request->isPost){

            $post = Yii::$app->request->post();
            $survey = SurveyList::find()->where([ 'id' => $post['id'] ])->one();
            if($survey['audit'] != 31){
                Yii::$app->session->setFlash('error','非法状态');
            }else{
                $data['sid'] = $post['id'];
                $data['flag'] = $post['audit'];
                $data['operator_id'] = Yii::$app->user->getId();
                $data['operator_name'] = Yii::$app->user->getIdentity()->username;
                $data['desc'] = $post['audit']==0 ? '分管副总审核未通过':'分管副总审核通过';
                $data['time'] = time();

                $transaction = Yii::$app->db->beginTransaction();
                try{
                    $survey->audit = ($post['audit'])==0 ? 40:41;
                    $res1 = $survey->save(false);
                    $res2 = AuditLog::addLog($data);

                    if($res1 && $res2){
                        $transaction->commit();
                        yii::$app->session->setFlash('success','审核成功');
                    }else{
                        $transaction->rollBack();
                        yii::$app->session->setFlash('error','审核失败');
                    }
                }catch (\Exception $e){
                    $transaction->rollBack();
                    yii::$app->session->setFlash('error','审核失败');
                }
            }
        }
        return $this->render('audit-one',[
            'model' => $survey,
        ]);
    }
}
