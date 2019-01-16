<?php

namespace backend\controllers;

use backend\models\Apijisu;
use backend\models\Channel;
use backend\models\Country;
use backend\models\ProblemLog;
use backend\models\Storage;
use backend\models\Upload;
use backend\models\User;
use backend\models\WaybillActioner;
use backend\models\WaybillConsignee;
use backend\models\WaybillFinance;
use backend\models\WaybillGoods;
use backend\models\WaybillProblem;
use backend\models\WaybillStatus;
use Yii;
use backend\models\Waybill;
use backend\models\WaybillSearch;
use yii\data\Pagination;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PHPExcel;
use yii\web\UploadedFile;

/**
 * WaybillController implements the CRUD actions for Waybill model.
 */
class WaybillController extends Controller
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
     * Lists all Waybill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WaybillSearch();

        $data = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('billConditions',Yii::$app->request->queryParams);

        $count = $data->count();

        $pager =  new Pagination([
            'totalCount' => $count,
            'pageSize' => 20,
        ]);
        $waybills = $data->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'waybills' => $waybills,
            'total' => $count,
            'pager' => $pager
        ]);
    }

    /**
     * 问题件列表（国内异常件）
     */
    public function actionProblemBill()
    {
        $searchModel = new WaybillSearch();
        $data = $searchModel->searchProblem(Yii::$app->request->queryParams);

        Yii::$app->session->set('conditions',Yii::$app->request->queryParams);

        $count = $data->count();
        $pager =  new Pagination([
            'totalCount' => $count,
            'pageSize' => 20,
        ]);
        $waybills = $data->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        return $this->render('problem-bill', [
            'searchModel' => $searchModel,
            'waybills' => $waybills,
            'total' => $count,
            'pager' => $pager
        ]);
    }
    /**
     * 上传文本文件批量查询
     * @return string
     */
    public function actionBatchSearch()
    {
        $model = new Upload();

        $type = '';
        $numArr = [];
        if(Yii::$app->request->isPost){
            $txt = UploadedFile::getInstance($model,'txt');
            if($txt){
                //保存查询的文本文件
                $txtname = date('YmdHis').'-'.rand(1,999).'.'.$txt->getExtension();
                $txt->saveAs(Yii::$app->basePath.'/uploads/'.$txtname);

                $type = Yii::$app->request->post()['numType'];

                $txtArr = file(Yii::$app->basePath.'/uploads/'.$txtname);
                $numArr = [];
                for ($i=0;$i<count($txtArr);$i++){
                   $numArr[] = trim($txtArr[$i]);
                }

                Yii::$app->session->set('type',$type);
                Yii::$app->session->set('numArr',$numArr);
            }
        }

        if(Yii::$app->session->has('type')){
            $type = Yii::$app->session->get('type');
        }
        if(Yii::$app->session->has('numArr')){
            $numArr = Yii::$app->session->get('numArr');
        }

        //查找
        $query = Waybill::find()->joinWith(['waybillStatus','waybillActioner','waybillConsignee'])->where(['in',$type,$numArr]);
        $count = $query->count();
        $pager = new Pagination([
            'totalCount' => $count,
            'pageSize' => 20,
        ]);

        $waybills = $query->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        //存入缓存 待导出备用
        Yii::$app->cache->set('batchSearch',$waybills);
        return $this->render('batch-search',[
            'model'=> $model,
            'total' => $count,
            'waybills' => $waybills,
            'pager' => $pager
        ]);
    }

    /**
     * 根据上传Excel表格批量修改运单信息
     * 并写入日志
     */
    public function actionBatchUpdate()
    {
        $model = new Upload();
        if(Yii::$app->request->isPost){
            $file = UploadedFile::getInstance($model,'file');
            if($file){
                //保存Excel文件
                $filename = date('YmdHis').'.'.$file->getExtension();
                $file->saveAs(Yii::$app->basePath.'/uploads/'.$filename);

                $billData = [];
                //读取文件，写入数组
                try{
                    $fileType = \PHPExcel_IOFactory::identify(Yii::$app->basePath.'/uploads/'.$filename);
                    $excelReader = \PHPExcel_IOFactory::createReader($fileType);

                    $excel = $excelReader->load(Yii::$app->basePath.'/uploads/'.$filename)->getSheet(0);
                    $totalLine = $excel->getHighestRow();
                    $totalColumn = $excel->getHighestColumn();

                    //指定修改的字段
                    $field = [
                        'B' =>'memberCode',
                        'C' => 'orderNum',
                        'D' => 'expressNum',
                        'E' => 'channelParentId',
                        'F' => 'channelChildId',
                        'G' => 'countryId',
                        'H' => 'remark'
                    ];
                    if($totalLine > 1){
                        for ($row = 2; $row <= $totalLine; $row++){
                            for ($column = 'B'; $column <= $totalColumn; $column++){
                                $key = $excel->getCell('A'.$row)->getValue();
                                $val = $excel->getCell($column.$row)->getValue();
                                if(!empty($val)){
                                    //渠道和国家需要转成对应Id
                                    if($field[$column] == 'channelParentId' || $field[$column] == 'channelChildId'){
                                        $channel = Channel::find()->where(['name' => $val])->asArray()->one();
                                        $val = $channel['id'];
                                    }
                                    if($field[$column] == 'countryId'){
                                        $country = Country::find()->where(['name' => $val])->asArray()->one();
                                        $val = $country['id'];
                                    }
                                    $billData[$key][$field[$column]] = $val;
                                }
                            }
                        }
                    }
                }catch (\Exception $e){
                    Yii::$app->session->setFlash('读取文件失败');
                    return $this->render('batch-update',[
                        'model' => $model
                    ]);
                }

                if(count($billData) > 0){
                    //根据提交上来的运单号，查询运单信息并记录下修改之前的字段信息
                    $waybills = Waybill::find()->where(['in','codeNum',array_keys($billData)])->asArray()->all();
                    //需插入问题件修改日志的数据
                    $p_log = [];
                    foreach ($waybills as $k=>$v){
                        $updateStr = '';
                        if(!empty($billData[$v['codeNum']])){
                            foreach ($billData[$v['codeNum']] as $fk=>$fv){
                                $updateStr .= '字段'.$fk.'由'.$v[$fk].'修改为'.$fv.';';
                            }
                            $p_log[] = [
                                'waybillId'=>$v['id'],
                                'codeNum' => $v['codeNum'],
                                'actionerId' => Yii::$app->user->getId(),
                                'actioner_name' => Yii::$app->user->getIdentity()->username,
                                'datetime' => time(),
                                'detail' => $updateStr,
                            ];
                        }
                    }

                    //操作数据库 1-批量修改 2-修改记录插入problem_log
                    $transaction = Yii::$app->db->beginTransaction();
                    try{
                        $res = true;
                        //循环批量修改
                        foreach ($billData as $k=>$v){
                            $res = $res && Yii::$app->db->createCommand()->update(Waybill::tableName(),$v,['codeNum'=>$k])->execute();
                        }
                        //批量插入problem_log
                        $res = $res && Yii::$app->db->createCommand()->batchInsert(ProblemLog::tableName(),['waybillId','codeNum','actionerId','actioner_name','datetime','detail'],$p_log)->execute();
                        if($res){
                            $transaction->commit();
                            Yii::$app->session->setFlash('msg','批量修改成功');
                        }else{
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('msg','批量修改失败，请检查文件格式是否是xls结尾或文件内容是否与模板文件一致');
                        }
                    }catch (\Exception $e){
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('msg',$e);
                    }
                }
            }
        }
        return $this->render('batch-update',[
            'model' => $model
        ]);
    }
    /**
     * Displays a single Waybill model.
     * @param integer $id
     * @param integer $timeIn
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $timeIn)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $timeIn),
        ]);
    }


    public function actionCheckIsProb()
    {
        $id = Yii::$app->request->get('id');
        $model = WaybillProblem::find()->where(['waybillId' => $id])->one();
        if(!empty($model)){
            return json_encode(['errorCode'=>1,'errorMsg'=>'该运单已经被设为问题件']);
        }else{
            return json_encode(['errorCode'=>0,'errorMsg'=>'success']);
        }
    }

    /**
     * 客服创建预录单出单
     * 1.先生成waybill,waybill_status,waybill_actioner,waybill_consignee,waybill_goods记录   增加waybill_finance
     * 2.获取waybillId 通过出单接口出单
     * 3.若申报价值与物品价值不符合，则无法出单；若有些子渠道没有对接，则无法出单，需客服手动填写
     * 4.出单后，返回转单号，客服继续填写备注等，此时操作即为添加修改
     */
    public function actionAutoApi()
    {
        //return json_encode(['errorCode'=>0,'errorMsg'=>'success','waybillId'=>12,'out'=>['waybill'=>1111111]]);
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();

            //字段验证
            $member = User::find()->where(['username'=>$post['memberName']])->one();
            if(is_null($member)){
                return json_encode(['errorCode'=>1,'errorMsg'=>'该会员不存在']);
            }

            $saveData = [];
            //waybill
            $saveData['Waybill'] = [
                'codeNum'=>$post['codeNum'],
                'orderNum'=>$post['orderNum'],
                'memberId'=>$member['id'],
                'memberName'=>$member['name'],
                'memberCode'=>$member['code'],
                'channelParentId'=>$post['channelParentId'],
                'channelChildId'=>$post['channelChildId'],
                'storageId'=>$post['storageId'],
                'countryId'=>$post['countryId'],
                'weightInput'=>$post['weightInput'],
                'timeIn' => time(),
                'declareValue'=>$post['declareValue'],
                'overWeightOut'=>$post['overWeightOut'],
                'valueInsured'=>$post['valueInsured'],
                'remark'=>$post['remark'],
                'remarkSpecial'=>$post['remarkSpecial'],
                'remarkMember'=>$post['remarkMember'],
            ];

            $saveData['WaybillStatus'] = [
                'comeFrom' => 999,
                'prerecord' => 1,
            ];

            $saveData['WaybillActioner'] = [
                'userCreate' => Yii::$app->user->getId(),
                'timeCreate' => time(),
                'userUpdate' => Yii::$app->user->getId(),
                'timeUpdate' => time(),
            ];

            $saveData['WaybillConsignee'] = [
                'consigneeName' => $post['consigneeName'],
                'consigneeTel' => $post['consigneeTel'],
                'consigneeCountry' => $post['countryId'],
                'consigneeState' => $post['consigneeState'],
                'consigneeCity' => $post['consigneeCity'],
                'consigneeCounty' => $post['consigneeCounty'],
                'consigneeZip' => $post['consigneeZip'],
                'consigneeAddress1' => $post['consigneeAddress1'],
            ];

            $saveData['WaybillFinance'] = [

            ];
            $goodsArr = [];
            foreach ($post['goods'] as $k=>$v){
                if($v!=''){
                    array_push($goodsArr,$v);
                }
            }

            $priceAll = array_sum(array_map(function ($v){return $v['price']*$v['quantity'];},$goodsArr));
            if($priceAll != $post['declareValue']){
                return json_encode(['errorCode'=>1,'errorMsg'=>'申报价值与物品价值不符']);
            }

            $transaction = Yii::$app->db->beginTransaction();
            try{
                $res = true;
                //添加waybill记录
                $waybillModel = new Waybill();
                $waybillModel->load($saveData);
                $res = $res && $waybillModel->save($saveData);
                $waybillId = Yii::$app->db->getLastInsertID();

                //添加waybill_status记录
                $saveData['WaybillStatus']['waybillId'] = $waybillId;
                $statusModel = new WaybillStatus();
                $statusModel->load($saveData);
                $res = $res && $statusModel->save($saveData);

                //添加waybill_actioner记录
                $saveData['WaybillActioner']['waybillId'] = $waybillId;
                $actionModel = new WaybillActioner();
                $actionModel->load($saveData);
                $res = $res && $actionModel->save($saveData);

                //添加waybill_consignee记录
                $saveData['WaybillConsignee']['waybillId'] = $waybillId;
                $consigneeModel = new WaybillConsignee();
                $consigneeModel->load($saveData);
                $res = $res && $consigneeModel->save($saveData);

                //添加waybill_finance记录
                $saveData['WaybillFinance']['waybillId'] = $waybillId;
                $financeModel = new WaybillFinance();
                $financeModel->load($saveData);
                $res = $res && $financeModel->save($saveData);

                //批量插入waybill_goods 记录
                foreach ($goodsArr as $k => $v) {
                    $goodsArr[$k]['waybillId'] = $waybillId;
                }

                $goods = array_map(function ($v){
                    return array_values($v);
                },$goodsArr);

                $res = $res && Yii::$app->db->createCommand()->batchInsert(WaybillGoods::tableName(),['nameCn','nameEn','hsCode','price','quantity','weight','waybillId'],$goods)->execute();

                if($res){
                    $transaction->commit();

                    //出单接口
                    $api = new Apijisu(['callId' => time()]);
                    $api_data = [
                        'waybillId' => $waybillId,
                    ];
                    $out = $api->autoApi($api_data);

                    if(!$out){
                        return json_encode(['errorCode'=>0,'errorMsg'=>'运单记录成功生成，出单失败','waybillId'=>$waybillId]);
                    }

                    if($out['code'] == 1){
                        return json_encode(['errorCode'=>0,'errorMsg'=>$out['message'],'waybillId'=>$waybillId]);
                    }

                    if($out['code'] == 0 && $out['data'] == ''){
                        return json_encode(['errorCode'=>0,'errorMsg'=>'运单已生成，该渠道无法出单，请通过第三方出单并手动填写','waybillId'=>$waybillId]);
                    }
                    return json_encode(['errorCode'=>0,'errorMsg'=>'操作成功','out'=>$out['data'],'waybillId'=>$waybillId]);

                }else{
                    $transaction->rollBack();
                    return json_encode(['errorCode'=>1,'errorMsg'=>'操作失败']);
                }
            }catch(\Exception $e){
                $transaction->rollBack();
                return json_encode(['errorCode'=>1,'errorMsg'=>'操作失败']);
            }
        }
    }

    /**
     * 完善运单的备注信息及转单号
     */
    public function actionFinishPrerecord()
    {
        $post = Yii::$app->request->post();
        $result = ['errorCode'=>1,'errorMsg'=>'系统错误'];
        $waybill = Waybill::find()->where(['id'=>$post['waybillId']])->asArray()->one();
        if(empty($waybill)){
            $result['errorMsg'] = '运单不存在';
        }
        $updateField = [
           'remark' => $post['remark'],
           'remarkSpecial' => $post['remarkSpecial'],
           'remarkMember' => $post['remarkMember'],
           'expressNum' => $post['expressNum'],
        ];
        $res = Yii::$app->db->createCommand()->update(Waybill::tableName(),$updateField,['id'=>$post['waybillId']])->execute();
        if($res){
            $result = ['errorCode'=>0,'errorMsg'=>'运单信息完善成功'];
        }
        return json_encode($result);
    }

    public function actionCreatePrerecord()
    {
        return $this->render('create',[
            'storage' => Storage::dropDrownList(),
        ]);
    }

    public function actionPrerecordList()
    {
        $searchModel = new WaybillSearch();
        $data = $searchModel->searchPrerecord(Yii::$app->request->queryParams);

        $count = $data->count();
        $pager = new Pagination([
          'totalCount' => $count,
          'pageSize' => 20,
        ]);
        $prerecords = $data->offset($pager->offset)->limit($pager->limit)->asArray()->all();
        return $this->render('prerecord',[
            'searchModel' => $searchModel,
            'prerecords' => $prerecords,
            'total' => $count,
            'pager' => $pager
        ]);

    }
    /*
     * Updates an existing Waybill model or WaybillStatus or WaybillConsignee or both of them
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Waybill::find()->joinWith(['waybillStatus','waybillConsignee'])->where(['ts_waybill.id'=>$id])->one();

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $waybill = Waybill::find()->joinWith(['waybillStatus','waybillConsignee'])->where(['ts_waybill.id'=>$id])->asArray()->one();

            //录入该订单修改前后的所有信息，拼接成为修改日志，待数据更新后写入问题件修改日志

            $updateBefore = [];
            $updateAfter = [];
            $logStr = '';
            foreach ($post['Waybill'] as $k=>$v){
                if(array_key_exists($k,$waybill)){
                    if($v != $waybill[$k]){
                        $updateBefore[$k] = $waybill[$k];
                        $updateAfter[$k] = $v;
                    }
                }elseif($waybill['waybillStatus'] && array_key_exists($k,$waybill['waybillStatus'])){
                    if($v != $waybill['waybillStatus'][$k]){
                        $updateBefore[$k] = $waybill['waybillStatus'][$k];
                        $updateAfter[$k] = $v;
                    }
                }elseif($waybill['waybillConsignee'] && array_key_exists($k,$waybill['waybillConsignee'])){
                    if($v != $waybill['waybillConsignee'][$k]){
                        $updateBefore[$k] = $waybill['waybillConsignee'][$k];
                        $updateAfter[$k] = $v;
                    }
                }else{
                    Yii::$app->session->setFlash('msg','该运单收件人表或运单状态表缺失');
                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }

//            //左连接，可能waybill存在记录而waybillStatus和waybillConsignee不存在，因此若这两表有修改字段，需要先查询是否有该记录
//            if($waybill['waybillConsignee'] && count(array_intersect(array_keys($updateAfter),array_keys($waybill['waybillConsignee']))) > 0){
//                //echo '修改了waybillConsignee表字段';die;
//                //查询waybillConsignee表是否有记录
//                $Status = WaybillStatus::find()->where(['waybillId'=>$id])->one();
//                if(empty($Status)){
//                    Yii::$app->session->setFlash('msg','该运单暂无收件人表');
//                    return $this->redirect(['update', 'id' => $model->id]);
//                }
//            }
//            if($waybill['waybillStatus'] && count(array_intersect(array_keys($updateAfter),array_keys($waybill['waybillStatus']))) > 0){
//                //echo '修改了waybillConsignee表字段';die;
//                //查询waybillStatus 表是否有记录
//                $Consignee = WaybillConsignee::find()->where(['waybillId'=>$id])->one();
//                if(empty($Consignee)){
//                    Yii::$app->session->setFlash('msg','该运单暂无运单状态表');
//                    return $this->redirect(['view', 'id' => $model->id]);
//                }
//            }

            if(empty($updateAfter)){
                Yii::$app->session->setFlash('msg','您并未修改任何数据');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }else{
                //修改数据
                $waybillModel = new Waybill();
                if($waybillModel->edit($updateAfter,$id)){
                    Yii::$app->session->setFlash('msg','修改成功');
                    //问题件运单修改 写入日志
                    if($waybill['waybillStatus']['statusAbnormalDomestic'] != 1 || $waybill['waybillStatus']['statusAbnormalForeign'] != 1){
                        foreach ($updateBefore as $k=>$v){
                            $logStr .= '字段' . $k .'由' . $v . '修改为' . $updateAfter[$k] . ';';
                        }
                        //写入问题件操作日志
                        $p_data['waybillId'] = $id;
                        $p_data['detail'] = $logStr;

                        ProblemLog::addLog($p_data);
                    }
                    return $this->redirect(['view', 'id' => $model->id,'timeIn'=>$model->timeIn]);
                }else{
                    Yii::$app->session->setFlash('msg','修改失败');
                }
            }
        }
        //var_dump($model);die;
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /*
     * Deletes an existing Waybill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $timeIn
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $timeIn)
    {
        $this->findModel($id, $timeIn)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Waybill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $timeIn
     * @return Waybill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $timeIn)
    {
        if (($model = Waybill::findOne(['id' => $id, 'timeIn' => $timeIn])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 查询超时运单
     */
    public function actionOvertime()
    {
        $searchModel = new WaybillSearch();
        $dataProvider = $searchModel->searchOverTime(Yii::$app->request->queryParams);

        return $this->render('overtime', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 转为正常件 操作记录写入问题件日志
     * 2018/11/20 文档增加修改：转为正常件后，入库时间改为当前
     */
    public function actionSetnormal($id)
    {
        //$model = $this->findModel($id,$timeIn);
        $waybillStatus = WaybillStatus::find()->where(['waybillId'=>$id])->one();
        //var_dump($waybillStatus);die;
        $waybillStatus->statusAbnormalDomestic = 1;
        $waybillStatus->statusAbnormalForeign = 1;
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $res = $waybillStatus->save(false);
            if(!$res){
                throw new \Exception('操作失败');
            }

            $p_data['waybillId'] = $id;
            $p_data['detail'] = '转为正常件';

            $res1 = ProblemLog::addLog($p_data);
            if(!$res1){
                throw new \Exception('操作失败');
            }

            //入库时间修改为当前时间
            $waybillActioner = WaybillActioner::find()->where(['waybillId'=>$id])->one();
            $waybillActioner->timeIn = time();
            $res2 = $waybillActioner->save(false);

            if(!$res2){
                throw new \Exception('操作失败');
            }

            $transaction->commit();
            return $this->redirect(['index']);
        }catch (\Exception $e){
            $transaction->rollBack();
        }
    }

    /**
     * 根据查询条件 导出 运单列表 2018/12/12
     */
    public function actionBillExport()
    {
        $queryParams = Yii::$app->session->get('billConditions');
        $searchModel = new WaybillSearch();
        $data = $searchModel->search($queryParams)->asArray()->all();

        $objPHPExcel = new PHPExcel();
        try{
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1','运单号')
                        ->setCellValue('B1','订单号')
                        ->setCellValue('C1','转单号')
                        ->setCellValue('D1','客户')
                        ->setCellValue('E1','渠道')
                        ->setCellValue('F1','目的国家')
                        ->setCellValue('G1','创建时间')
                        ->setCellValue('H1','入库时间')
                        ->setCellValue('I1','出库时间')
                        ->setCellValue('J1','入库重量')
                        ->setCellValue('K1','出库重量')
                        ->setCellValue('L1','特殊要求')
                        ->setCellValue('M1','备注')
                        ->setCellValue('N1','航班号')
                        ->setCellValue('O1','状态');
            $channel = Channel::dropDrownList();
            $country = Country::dropDrownList();
            $n = 2;
            foreach ($data as $v){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.($n),$v['codeNum']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.($n),$v['orderNum']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.($n),$v['expressNum']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.($n),$v['memberName']);
                $channel_name = '';
                if(isset($channel[$v['channelParentId']])){
                    $channel_name .= $channel[$v['channelParentId']].'-';
                }
                if(isset($channel[$v['channelChildId']])){
                    $channel_name .= $channel[$v['channelChildId']];
                }
                $objPHPExcel->getActiveSheet()->setCellValue('E'.($n),$channel_name);
                $country_name = isset($country[$v['countryId']])? $country[$v['countryId']]:'';
                $objPHPExcel->getActiveSheet()->setCellValue('F'.($n),$country_name);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.($n),empty($v['waybillActioner']['timeCreate'])? '':date('Y-m-d H:i:s',$v['waybillActioner']['timeCreate']));
                $objPHPExcel->getActiveSheet()->setCellValue('H'.($n),empty($v['waybillActioner']['timeIn'])? '':date('Y-m-d H:i:s',$v['waybillActioner']['timeIn']));
                $objPHPExcel->getActiveSheet()->setCellValue('I'.($n),empty($v['waybillActioner']['timeOut'])? '':date('Y-m-d H:i:s',$v['waybillActioner']['timeOut']));
                $objPHPExcel->getActiveSheet()->setCellValue('J'.($n),$v['weightInput']);
                $objPHPExcel->getActiveSheet()->setCellValue('K'.($n),$v['weightOutput']);
                $objPHPExcel->getActiveSheet()->setCellValue('L'.($n),$v['remarkSpecial']);
                $objPHPExcel->getActiveSheet()->setCellValue('M'.($n),$v['remark']);
                $objPHPExcel->getActiveSheet()->setCellValue('N'.($n),$v['flyNo']);
                $objPHPExcel->getActiveSheet()->setCellValue('O'.($n),Yii::$app->params['waybillStatus'][$v['waybillStatus']['status']]);

                $n++;
            }

            //单元格居中
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            ob_end_clean();
            ob_start();

            header('Content-Type:application/vnd.ms-excel');
            //设置输出文件名及格式
            header('Content-Disposition:attachment;filename="'.'运单列表-'.date("YmdHis").'.xls"');

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            $objWriter->save("php://output");
            ob_flush();
        }catch (\Exception $e){
            echo $e;exit;
        }
    }

    /**
     * 根据查询条件导出 问题件列表  2018/12/13
     */
    public function actionProblemExport()
    {
        $queryParams = Yii::$app->session->get('conditions');
        $searchModel = new WaybillSearch();
        $data = $searchModel->searchProblem($queryParams)->asArray()->all();

        $objPHPExcel = new PHPExcel();
        try{
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','入库日期')
                ->setCellValue('B1','订单编号')
                ->setCellValue('C1','客户名')
                ->setCellValue('D1','渠道')
                ->setCellValue('E1','问题件类型')
                ->setCellValue('F1','问题件状态')
               ;
            $channel = Channel::dropDrownList();
            $n = 2;
            foreach ($data as $v){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.($n),empty($v['waybillActioner']['timeIn'])? '':date('Y-m-d H:i:s',$v['waybillActioner']['timeIn']));
                $objPHPExcel->getActiveSheet()->setCellValue('B'.($n),$v['orderNum']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.($n),$v['memberName']);
                $channel_name = '';
                if(isset($channel[$v['channelParentId']])){
                    $channel_name .= $channel[$v['channelParentId']].'-';
                }
                if(isset($channel[$v['channelChildId']])){
                    $channel_name .= $channel[$v['channelChildId']];
                }
                $objPHPExcel->getActiveSheet()->setCellValue('D'.($n),$channel_name);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.($n),Yii::$app->params['abnormalDomesticStatus'][$v['waybillStatus']['statusAbnormalDomestic']]);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.($n),Yii::$app->params['waybillStatus'][$v['waybillStatus']['status']]);

                $n++;
            }

            //单元格居中
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            ob_end_clean();
            ob_start();

            header('Content-Type:application/vnd.ms-excel');
            //设置输出文件名及格式
            header('Content-Disposition:attachment;filename="'.'问题件列表-'.date("YmdHis").'.xls"');

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            $objWriter->save("php://output");
            ob_flush();
        }catch (\Exception $e){
            echo $e;exit;
        }
    }

    /**
     *  导出批量查询的运单
     */
    public function actionBatchExport()
    {
        $data = Yii::$app->cache->get('batchSearch');
        $objPHPExcel = new PHPExcel();
        try{
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','运单号')
                ->setCellValue('B1','订单号')
                ->setCellValue('C1','转单号')
                ->setCellValue('D1','客户')
                ->setCellValue('E1','渠道')
                ->setCellValue('F1','目的国家')
                ->setCellValue('G1','创建时间')
                ->setCellValue('H1','入库时间')
                ->setCellValue('I1','出库时间')
                ->setCellValue('J1','入库重量')
                ->setCellValue('K1','出库重量')
                ->setCellValue('L1','特殊要求')
                ->setCellValue('M1','备注')
                ->setCellValue('N1','航班号')
                ->setCellValue('O1','状态');
            $channel = Channel::dropDrownList();
            $country = Country::dropDrownList();
            $n = 2;
            foreach ($data as $v){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.($n),$v['codeNum']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.($n),$v['orderNum']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.($n),$v['expressNum']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.($n),$v['memberName']);
                $channel_name = '';
                if(isset($channel[$v['channelParentId']])){
                    $channel_name .= $channel[$v['channelParentId']] . '-';
                }
                if(isset($channel[$v['channelChildId']])){
                    $channel_name .= $channel[$v['channelChildId']];
                }
                $objPHPExcel->getActiveSheet()->setCellValue('E'.($n),$channel_name);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.($n),$country[$v['countryId']]);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.($n),empty($v['waybillActioner']['timeCreate'])? '':date('Y-m-d H:i:s',$v['waybillActioner']['timeCreate']));
                $objPHPExcel->getActiveSheet()->setCellValue('H'.($n),empty($v['waybillActioner']['timeIn'])? '':date('Y-m-d H:i:s',$v['waybillActioner']['timeIn']));
                $objPHPExcel->getActiveSheet()->setCellValue('I'.($n),empty($v['waybillActioner']['timeOut'])? '':date('Y-m-d H:i:s',$v['waybillActioner']['timeOut']));
                $objPHPExcel->getActiveSheet()->setCellValue('J'.($n),$v['weightInput']);
                $objPHPExcel->getActiveSheet()->setCellValue('K'.($n),$v['weightOutput']);
                $objPHPExcel->getActiveSheet()->setCellValue('L'.($n),$v['remarkSpecial']);
                $objPHPExcel->getActiveSheet()->setCellValue('M'.($n),$v['remark']);
                $objPHPExcel->getActiveSheet()->setCellValue('N'.($n),$v['flyNo']);
                $objPHPExcel->getActiveSheet()->setCellValue('O'.($n),Yii::$app->params['waybillStatus'][$v['waybillStatus']['status']]);

                $n++;
            }

            //单元格居中
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            ob_end_clean();
            ob_start();
            header('Content-type:application/vnd.ms-excel');
            //设置输出文件名及格式
            header('Content-Disposition:attachment;filename="'.'运单列表-'.date('YmdHis').'.xls"');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            $objWriter->save("php://output");
            ob_flush();
        }catch (\Exception $e){
            echo $e;exit;
        }
    }
    /**
     * 导出 运单超时
     */
    public function actionExport()
    {
        $searchModel = new WaybillSearch();
        $searchData = $searchModel->exportovertime(Yii::$app->request->queryParams);
        //var_dump($searchData);die;
        $objPHPExcel = new PHPExcel();
        try{
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1','入库日期')
                        ->setCellValue('B1','类型(补全超时/出库超时)')
                        ->setCellValue('C1','订单号')
                        ->setCellValue('D1','客户名')
                        ->setCellValue('E1','渠道')
                        ->setCellValue('F1','补全日期')
                        ->setCellValue('G1','出库日期');

            $channel = Channel::dropDrownList();
            $n = 2;
            foreach ($searchData as $v){
                //var_dump($v['waybill'])
                $objPHPExcel->getActiveSheet()->setCellValue('A'.($n),$v['waybillActioner']['timeIn'] ? date('Y-m-d H:i:s',$v['waybillActioner']['timeIn']):'');
                if(empty($v['waybillActioner']['timeUpdate'])){
                    $type = '补全超时';
                }else{
                    $type = '出库超时';
                }
                $objPHPExcel->getActiveSheet()->setCellValue('B'.($n),$type);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.($n),$v['orderNum']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.($n),$v['memberName']);
                $channel_name = $channel[$v['channelParentId']].'(父渠道)-'.$channel[$v['channelChildId']].'(子渠道)';
                $objPHPExcel->getActiveSheet()->setCellValue('E'.($n),$channel_name);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.($n),$v['waybillActioner']['timeUpdate']? date('Y-m-d H:i:s',$v['waybillActioner']['timeUpdate']):'');
                $objPHPExcel->getActiveSheet()->setCellValue('G'.($n),$v['waybillActioner']['timeOut']?date('Y-m-d H:i:s',$v['waybillActioner']['timeOut']):'');
                $n++;
            }

            //单元格居中
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            ob_end_clean();
            ob_start();

            header('Content-Type:application/vnd.ms-excel');
            //设置输出文件名及格式
            header('Content-Disposition:attachment;filename="'.'正常件超时运单-'.date("YmdHis").'.xls"');

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            $objWriter->save('php://output');
            ob_flush();
        }catch(\Exception $e){
            echo $e;exit;
        }
    }
    



}
