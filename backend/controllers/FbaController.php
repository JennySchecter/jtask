<?php

namespace backend\controllers;

use backend\models\FbaAnnex;
use Yii;
use backend\models\Fba;
use backend\models\FbaSearch;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FbaController implements the CRUD actions for Fba model.
 */
class FbaController extends Controller
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
     * Lists all Fba models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FbaSearch();
        $data = $searchModel->search(Yii::$app->request->queryParams);

        $count = $data->count();
        $pager = new Pagination([
            'totalCount'=>$count,
            'pageSize' => 20,
        ]);

        $fbaLists = $data->offset($pager->offset)->limit($pager->limit)->asArray()->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'total' => $count,
            'fbaLists' => $fbaLists,
            'pager' => $pager,
        ]);
    }

    public function actionDownload()
    {
        $id = Yii::$app->request->get('fid');
        $attachments = FbaAnnex::find()->where(['fbaId'=>$id])->asArray()->all();
        return $this->renderAjax('download',[
            'attachments' => $attachments,
        ]);
    }
    /**
     * Displays a single Fba model.
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
     * Creates a new Fba model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Fba();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Fba model.
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


    public function actionDelete()
    {
        $id = Yii::$app->request->post('fid');
        $result = ['errorCode' => 1,'errorMsg' => '系统错误'];
        $model = Fba::find()->where(['id'=>$id])->one();
        if(!$model){
            $result['errorMsg'] = '未找到该fba运单';
        }elseif($model['recycle']==1){
            $result['errorMsg'] = 'fba运单状态异常';
        }else{
            //伪删除
            $model->recycle = 1;
            if($model->save(false)){
                $result = ['errorCode' => 0,'errorMsg' => '删除成功'];
            }else{
                $result['errorMsg'] = '删除失败';
            }
        }
        return json_encode($result);
    }

    /**
     * Finds the Fba model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fba the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fba::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
