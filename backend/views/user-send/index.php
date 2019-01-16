<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\AppAsset;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSendSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//加载user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/user.js');

$this->title = '客户寄件';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-send-index">

    <?php
    try{
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                [
                    'attribute' => 'userid',
                    'format' => 'raw',
                    'label' => '会员名',
                    'value' => function($model){
                        $userinfo = \backend\models\User::find()->where(['id'=>$model->userid])->one();
                        return $userinfo['name'];
                    }
                ],
                'username',
                'company',
                'expressNum',
                'sendername',
                'sendermobile',
                //'nums',
                //'weight',
                //'item',
                [
                    'format' => 'raw',
                    'label' => '状态',
                    'attribute' => 'status',
                    'value' => function($model){
                        if($model->status==0){
                            return '已提交';
                        }elseif($model->status==1){
                            return '问题件';
                        }else{
                            return '已确认收货';
                        }
                    }
                ],
                'c_time:datetime',
                [
                    'format'=> 'raw',
                    'attribute'=>'up_time',
                    'value' => function($model){
                           return $model->up_time!='' ? date('Y-m-d H:i:s',$model->up_time):'';
                    }
                ],
//                [
//                    'format' => 'raw',
//                    'attribute' => 'handleId',
//                    'value' => function($model){
//                            return $model->handleId!='' ? $model->handleId:'';
//                    }
//                ],
//                'memremark',
//                [
//                    'format'  => 'raw',
//                    'attribute' => 'remark',
//                    'value' => function($model){
//                        return $model->remark!='' ? $model->remark:'';
//                    }
//                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}{setprob}{receipt}',
                    'header' => '操作',
                    'buttons' => [
                        'view'=>function($url,$model,$key){
                            return Html::a('<button class="btn btn-sm btn-info">查看</button>',$url);
                        },
                        'setprob'=>function($url,$model,$key){
                                return Html::a('<button class="btn btn-sm btn-info">设置问题件</button>',$url);
                        },
                        'receipt' =>function($url,$model,$key){
                            return Html::button('确认收货',['class'=>'btn btn-sm btn-primary receipt','key'=>$key]);
                        }
                    ],
                    'headerOptions' => ['width' => '180'],
                ],
            ],
        ]);
    }catch (\Exception $e){

    }
    ?>
</div>
