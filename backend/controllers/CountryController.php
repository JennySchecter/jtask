<?php

namespace backend\controllers;
use yii\web\Controller;
use Yii;
use backend\models\Country;
class CountryController extends Controller{
    public function actionSearchCountry($q)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '','text' => '']];
        if(!$q){
            return $out;
        }
        $data = Country::find()
                ->select('id,name as text')
                ->andFilterWhere(['like','name',$q])
                ->limit(50)
                ->asArray()
                ->all();
        $out['results'] = array_values($data);
        return $out;
    }
}