<?php

namespace backend\controllers;
use backend\models\AutoAddress;
use yii\web\Controller;
use Yii;

class AutoAddressController extends Controller{
    public function actionAutoFill()
    {
        $consigneeZip = Yii::$app->request->post('consigneeZip');
        $address = AutoAddress::find()->where(['zip'=>$consigneeZip])->asArray()->one();
        return json_encode(['address'=>$address]);
    }
}