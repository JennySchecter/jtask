<?php

namespace backend\controllers;

use backend\models\ProblemLog;
use backend\models\UserAlarms;
use backend\models\Waybill;
use backend\models\WaybillStatus;
use Yii;
use backend\models\WaybillProblem;
use backend\models\WaybillProblemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \PHPExcel;
/**
 * WaybillProblemController implements the CRUD actions for WaybillProblem model.
 */
class WaybillProblemController extends Controller
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
     * Lists all WaybillProblem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WaybillProblemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExport()
    {
        $searchModel = new WaybillProblemSearch();
        $searchData = $searchModel->export(Yii::$app->request->queryParams);

        $objPHPExcel = new PHPExcel();
        try{
            //设置表头
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','入库日期')
                ->setCellValue('B1','设置问题件日期')
                ->setCellValue('C1','订单编号')
                ->setCellValue('D1','客户名')
                ->setCellValue('E1','渠道')
                ->setCellValue('F1','问题件类型')
                ->setCellValue('G1','问题件状态');

            $n = 2;
            foreach ($searchData as $v){

                $objPHPExcel->getActiveSheet()->setCellValue('A'.($n),$v['waybill']['timeIn']? date('Y-m-d H:i:s',$v['waybill']['timeIn']):'' );

                $objPHPExcel->getActiveSheet()->setCellValue('B'.($n), date('Y-m-d H:i:s',$v['c_time']) );

                $objPHPExcel->getActiveSheet()->setCellValue('C'.($n),$v['waybill']['orderNum']);

                $objPHPExcel->getActiveSheet()->setCellValue('D'.($n),$v['waybill']['memberName']);

                $objPHPExcel->getActiveSheet()->setCellValue('E'.($n),$v['waybill']['channelParentId']);

                $objPHPExcel->getActiveSheet()->setCellValue('F'.($n),$v['remark']);

                if($v['deal_status'] == 0){
                    $status = '未处理';
                }elseif($v['deal_status' == 1]){
                    $status = '处理中';
                }else{
                    $status = '处理完成';
                }
                $objPHPExcel->getActiveSheet()->setCellValue('G'.($n),$status);
                $n++;
            }
            //单元格居中
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            ob_end_clean();
            ob_start();

            header('Content-Type:application/vnd.ms-excel');
            //设置输出文件名及格式
            header('Content-Disposition:attachment;filename="'.'问题件-'.date("YmdHis").'.xls"');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            $objWriter->save('php://output');
            ob_flush();
        }catch(\Exception $e){
            echo $e;exit;
        }
    }
    /**
     * Displays a single WaybillProblem model.
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

    /*
     *改变运单状态 创建问题件记录  创建完成后，写入操作记录，发送站内信提醒用户
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WaybillProblem();
        $id = Yii::$app->request->get('id');
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();

            //运单
            $waybill = Waybill::find()->where(['id'=>$id])->one();

            $alarms['userId'] = $waybill['memberId'];
            $alarms['subject'] = '问题件';
            $alarms['content'] = '您的id为'.$waybill['memberId'].'的运单，已被设为问题件,请前往处理';
            $alarms['datetime'] = time();
            //开启事务
            $transaction = Yii::$app->db->beginTransaction();
            try{
                //改变运单异常件状态
                $waybillStatus = WaybillStatus::find()->where(['waybillId'=>$waybill['id']])->one();
                if($post['out'] == 1){  //出库前异常
                    $waybillStatus->statusAbnormalDomestic = 2;
                }else{
                    $waybillStatus->statusAbnormalForeign = 2;
                }
                $res = $waybillStatus->save(false);
                if(!$res){
                    throw new \Exception('设置问题件操作失败!');
                }
                //添加问题件记录
                $res1 = $model->create($post);
                if(!$res1){
                    throw new \Exception('设置问题件操作失败!');
                }
                $p_data['waybillId'] = $id;
                $p_data['detail'] = '设置成为问题件';

                //添加问题件日志记录
                $res2 = ProblemLog::addLog($p_data);
                if(!$res2){
                    throw new \Exception('生成问题件日志操作失败!');
                }

                //发送邮件

                //给用户发送站内信
                $ualarm = new UserAlarms();
                $res3 = $ualarm->create($alarms);
                if(!$res3){
                    throw new \Exception('站内信发送失败');
                }

                $transaction->commit();
                return $this->redirect(['index']);
            }catch (\Exception $e){
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'id' => $id
        ]);
    }

    /**
     * Updates an existing WaybillProblem model.
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
     * Deletes an existing WaybillProblem model.
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
     * Finds the WaybillProblem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WaybillProblem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WaybillProblem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 问题件处理后 生成操作记录
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDeal($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            if($model->deal($post,$id)){
                $p_data['waybillId'] = $id;
                $p_data['detail'] = '添加处理意见:'.$post['WaybillProblem']['dealcontent'];

                $res = ProblemLog::addLog($p_data);
                if($res){
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('deal', [
            'model' => $this->findModel($id),
        ]);
    }
}
