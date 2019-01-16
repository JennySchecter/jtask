<?php

namespace backend\controllers;
use yii\web\Controller;
use Yii;
use backend\models\Storage;
class StorageController extends Controller{
    public function actionSearchStorage($q)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '','text' => '']];
        if(!$q){
            return $out;
        }
        $data = Storage::find()
                ->select('id,name as text')
                ->andFilterWhere(['like','name',$q])
                ->limit(50)
                ->asArray()
                ->all();
        $out['results'] = array_values($data);
        return $out;
    }
}