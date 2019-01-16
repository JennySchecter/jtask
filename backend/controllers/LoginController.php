<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 2018/9/17
 * Time: 0:37
 */
namespace backend\controllers;

use yii;
use yii\web\Controller;
use backend\models\Admin;

class LoginController extends Controller{

    //登入
    public function actionLogin()
    {
        $model = new Admin();
        if(yii::$app->request->isPost){
            $post = yii::$app->request->post();
            if($model->login($post)){
                $this->redirect(['index/index']);
            }
        }
        return $this->renderPartial('login',['model' => $model]);
    }

    //登出
    public function actionLoginout()
    {
        yii::$app->session->removeAll();
        if(!yii::$app->session->has(yii::$app->session['admin']['isLogin'])){
            $this->redirect(['index/index']);
        }
    }
}