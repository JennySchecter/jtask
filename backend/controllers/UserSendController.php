<?php

namespace backend\controllers;

use Yii;
use backend\models\UserSend;
use backend\models\UserSendSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserSendController implements the CRUD actions for UserSend model.
 */
class UserSendController extends Controller
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
     * Lists all UserSend models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSendSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserSend model.
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
     * Creates a new UserSend model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserSend();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * 设问题件并注明原因
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSetprob($id)
    {
        $model = $this->findModel($id);

        if(yii::$app->request->isPost){
            //设为问题件（不同于运单问题）并推送站内信给客户
            $post = yii::$app->request->post();
            if($model->setProb($post,$id)){
                $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /*
     * 客户寄件确认收货
     */
    public function actionReceipt()
    {
        return json_encode(['errorCode' => 0, 'errorMsg' => '操作成功']);
        /*$id = Yii::$app->request->get('id');
        $model = new UserSend();
        if($model->receipt($id)){
            return json_encode(['errorCode' => 0, 'errorMsg' => '操作成功']);
        }else{
            return json_encode(['errorCode' => 1, 'errorMsg' => '操作失败']);
        }*/
    }
    /*
     * Deletes an existing UserSend model.
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
     * Finds the UserSend model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserSend the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserSend::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
