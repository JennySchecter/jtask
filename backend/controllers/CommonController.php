<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;


/**
 * Site controller
 */
class CommonController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {

    }

    //是否登录
    public function beforeAction($action)
    {
        if(!\yii::$app->session['admin']['isLogin']){
            $this->redirect(['index/index']);
            return false;
        }
        return parent::beforeAction($action);
    }

}
