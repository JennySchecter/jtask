<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 2018/9/21
 * Time: 23:16
 */

namespace frontend\controllers;

use yii;
use frontend\models\LoginForm;
use yii\web\Controller;

class IndexController extends CommonController
{
    public function actionIndex()
    {
        var_dump(yii::$app->user);

    }
}