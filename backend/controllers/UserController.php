<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\Group;
use backend\models\Upload;
use backend\models\UserAlarms;
use backend\models\Waybill;
use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \PHPExcel;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $groups = Group::find()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'groups' => $groups
        ]);
    }

    /**
     * 批量导入客户
     */

    public function actionImport()
    {
        $model = new Upload();
        if(Yii::$app->request->isPost){
            $file = UploadedFile::getInstance($model,'file');
            if($file){
                $filename = date('Y-m-d').'_'.rand(1,9999).'.'.$file->getExtension();
                $file->saveAs(Yii::$app->basePath.'/uploads/'.$filename);

                //导入数据
                try{
                    $fileType = \PHPExcel_IOFactory::identify(Yii::$app->basePath.'/uploads/'.$filename);
                    $excelReader = \PHPExcel_IOFactory::createReader($fileType);

                    $excel = $excelReader->load(Yii::$app->basePath.'/uploads/'.$filename)->getSheet(0);
                    $totalLine = $excel->getHighestRow();
                    $totalColumn = $excel->getHighestColumn();

                    if($totalLine > 1){
                        $userData = [];
                        for ($row = 2;$row <= $totalLine;$row++){
                            $user = [];
                            for ($column = 'A';$column <= $totalColumn; $column++){
                                $user[] = trim($excel->getCell($column.$row)->getValue());
                            }
                            $userData[] = $user;
                        }
                        $userField = [
                            'username','email','name','code','mobile','qq','address','balance','creditMoney'
                        ];

                        $query = Yii::$app->db->createCommand()->batchInsert(User::tableName(),$userField,$userData)->execute();
                        if($query){
                            Yii::$app->session->setFlash('msg','批量导入成功！');
                        }else{
                            Yii::$app->session->setFlash('msg','批量导入失败！');
                        }
                    }

                }catch(\Exception $e){
                    //throw new Exception($e);
                    Yii::$app->session->setFlash('msg','操作失败');
                }
            }
        }
        return $this->render('import',['model'=>$model]);
    }
    /*
     * 导出用户资料表
     */
    public function actionExport()
    {
        $queryParams = Yii::$app->request->queryParams['UserSearch'];
        $conditions = [];

        //只能看到自己服务组的客户信息
        $admin = Admin::findIdentity(Yii::$app->user->getId());
        $group_id = $admin['groupid'];
        $conditions['groupid']=$group_id;

        if(!empty($queryParams['id'])){
            $conditions['id'] = $queryParams['id'];
        }
        if(!empty($queryParams['username'])){
            $conditions['username'] = $queryParams['username'];
        }
        if(!empty($queryParams['name'])){
            $conditions['name'] = $queryParams['name'];
        }
        if(!empty($queryParams['groupid'])){
            $conditions['groupid'] = $queryParams['groupid'];
        }
        if(!empty($queryParams['status'])){
            $conditions['status'] = $queryParams['status'];
        }
        if(!empty($queryParams['isvip'])){
            $conditions['isvip'] = $queryParams['isvip'];
        }
        if(empty($conditions)){
            $users = User::find()->all();
        }else{
            $users = User::find()->where($conditions)->all();
        }

        $objPHPExcel = new PHPExcel();
        try{
            //设置表头
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1','ID')
                ->setCellValue('B1','客户账号')
                ->setCellValue('C1','邮箱')
                ->setCellValue('D1','客户名称')
                ->setCellValue('E1','简码')
                ->setCellValue('F1','手机号码')
                ->setCellValue('G1','微信号')
                ->setCellValue('H1','QQ号')
                ->setCellValue('I1','地址1')
                ->setCellValue('J1','证件号码')
                ->setCellValue('K1','账户余额')
                ->setCellValue('L1','大货小包限额')
                ->setCellValue('M1','付款方式')
                ->setCellValue('N1','状态')
                ->setCellValue('O1','组别')
                ->setCellValue('P1','vip');

            $n = 2;
            //填入数据
            foreach ($users as $v){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.($n),$v['id']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.($n),$v['username']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.($n),$v['email']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.($n),$v['name']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.($n),$v['code']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.($n),$v['mobile']);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.($n),$v['wechat']);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.($n),$v['qq']);
                $objPHPExcel->getActiveSheet()->setCellValue('I'.($n),$v['address']);
                $objPHPExcel->getActiveSheet()->setCellValue('J'.($n),$v['paperworkCode']);
                $objPHPExcel->getActiveSheet()->setCellValue('K'.($n),$v['balance']);
                $objPHPExcel->getActiveSheet()->setCellValue('L'.($n),$v['balance1']);
                switch ($v['payType']){
                    case 1:
                        $paytype = '现付';break;
                    case 2:
                        $paytype = '周结';break;
                    case 3:
                        $paytype = '半月结';break;
                    default:
                        $paytype = '月结';
                }
                $objPHPExcel->getActiveSheet()->setCellValue('M'.($n),$paytype);
                $objPHPExcel->getActiveSheet()->setCellValue('N'.($n),$v['status']);
                $objPHPExcel->getActiveSheet()->setCellValue('O'.($n),$v['groupid']);

                $objPHPExcel->getActiveSheet()->setCellValue('P'.($n),$v['isvip']==0? '否':'是');
                $n++;
            }
            //单元格居中
            $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

            ob_end_clean();
            ob_start();

            header('Content-Type:application/vnd.ms-excel');
            //设置输出文件名及格式
            header('Content-Disposition:attachment;filename="'.'用户信息表-'.date("YmdHis").'.xls"');

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            $objWriter->save('php://output');
            ob_flush();
        }catch (\Exception $e){
            echo $e;exit();
        }

    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
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
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * 手动设置为vip用户
     */
    public function actionSetvip()
    {
        $id = yii::$app->request->get('id');
        if(User::updateAll(['isvip'=>1],['id'=>$id])){
            return json_encode(['errorCode'=>0,'errorMsg'=>'设置成功']);
        }else{
            return json_encode(['errorCode'=>1,'errorMsg'=>'设置失败']);
        }
    }

    /**
     * 手动取消vip用户
     */
    public function actionCancelvip()
    {
        $id = yii::$app->request->get('id');
        if(User::updateAll(['isvip'=>0],['id'=>$id])){
            return json_encode(['errorCode'=>0,'errorMsg'=>'取消成功']);
        }else{
            return json_encode(['errorCode'=>1,'errorMsg'=>'取消失败']);
        }
    }

    /*
     * 批量为客户设置分组
     */
    public function actionSetgroup()
    {
        $ids = yii::$app->request->get('ids');
        $gid = yii::$app->request->get('gid');
        //判断传递来的数据gid的正确性
        $group = Group::find()->where('id=:gid',[':gid'=>$gid])->one();
        if(is_null($group)){
            return json_encode(['errorCode'=>1,'errorMsg'=>'该分组不存在']);
        }
        //若分组存在，则批量更新客服groupid
        $model = new User();
        if($model->setGroup($ids,$gid)){
            return json_encode(['errorCode'=>0,'errorMsg'=>'分组成功']);
        }else{
            return json_encode(['errorCode'=>1,'errorMsg'=>'分组失败']);
        }
    }
}
