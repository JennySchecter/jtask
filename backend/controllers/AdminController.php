<?php

namespace backend\controllers;

use backend\helpers\AdminFun;
use backend\models\Group;
use backend\models\KfGroup;
use mdm\admin\components\Helper;
use Yii;
use backend\models\Admin;
use backend\models\AdminSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\swiftmailer\Mailer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class AdminController extends Controller
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

    /*
     * Lists all Admin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $groups = Group::find()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'groups' =>$groups
        ]);
    }

    public function actionChangePwd()
    {
        $model =  Admin::findIdentity(Yii::$app->user->getId());
        if(Yii::$app->request->isPost){
            if($model->changePwd(Yii::$app->request->post(),Yii::$app->user->getId())){
                Yii::$app->session->setFlash('msg','修改成功，立刻'.Html::a('重新登录',['/site/logout'],['data-method' => 'post']));
            }else{
                Yii::$app->session->setFlash('msg','修改失败');
            }
        }
        return $this->render('change-pwd',[
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Admin model.
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
     * Creates a new Admin model.
     */
    public function actionCreate()
    {
        $model = new \backend\models\CreateForm();

        if($model->load(Yii::$app->request->post()) && $model->create()){
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            //过滤post里不必更新的字段
            unset($post['Admin']['username']);
            unset($post['Admin']['datetime']);
            if($model->updateSingle($post,$id)){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        //获取客户分组列表
        $gmodel = new Group();
        $glists = $gmodel->getOption();
        //获取客服分组列表
        $kmodel = new KfGroup();
        $klists = $kmodel->getOption();
        return $this->render('update', [
            'model' => $model,
            'glists' => $glists,
            'klists' => $klists
        ]);
    }

    /*
     * Deletes an existing Admin model.
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
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    /*
     * 批量为后台用户设置分组
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
        $model = new Admin();
        if($model->setGroup($ids,$gid)){
            return json_encode(['errorCode'=>0,'errorMsg'=>'分组成功']);
        }else{
            return json_encode(['errorCode'=>1,'errorMsg'=>'分组失败']);
        }
    }

    public function actionSendmail()
    {
        $mail = Yii::$app->mailer->compose('test');
        $mail->setFrom('805729623@qq.com');
        $mail->setTo('805729623@qq.com');
        $mail->setSubject('2018/10/16');
        if($mail->send()){
            echo 'success';
        }else{
            echo 'error';
        }
    }

    /**
     * 设置配置表值 2018/10/24
     */
    public function actionSetting()
    {
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $res = AdminFun::Config('greetings',$post['greetings']);
            if($res){
                Yii::$app->session->setFlash('msg','设置成功');
            }
        }
        $greetings = AdminFun::Config('greetings');
        return $this->render('setting',[
            'greetings' => $greetings,
        ]);
    }

    /**
     * 后台用户检索下拉列表
     */
    public function actionSearchAdmin($q)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '','text' => '']];
        if(!$q){
            return $out;
        }
        $data = Admin::find()
                ->select('id,username as text')
                ->andFilterWhere(['like','username',$q])
                ->limit(50)
                ->asArray()
                ->all();
        $out['results'] = array_values($data);
        return $out;
    }
}
