<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\UserSendSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我要寄件';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-send-index">

    <?php
    try{
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
               //['class' => 'yii\grid\SerialColumn'],

                'id',
                'company',
                'expressNum',
                'sendername',
                'sendermobile',
                'nums',
                'weight',
                'item',
                [
                    'format'=>'raw',
                    'label'=>'状态',
                    'attribute' => 'status',
                    'value'=>function($model){
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
                //'up_time:datetime',
                //'handleId',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=> '{view}{update}{delete}',
                    'buttons' => [
                        'update' => function($url,$model,$key){
                                return $model['status']==0 ? Html::a('<span class="glyphicon glyphicon-pencil"></span>',$url,
                                    ['data' => ['method' => 'post', 'id' => $key, 'type' => 'on']]):'';
                            },
                        'delete' => function($url,$model,$key){
                            return $model['status']==0 ? Html::a('<span class="glyphicon glyphicon-trash"></span>',$url,
                                ['data' => ['confirm'=>'您确定要删除吗','method' => 'post', 'id' => $key, 'type' => 'on']]):'';
                        },
                    ],
                ],
            ],
        ]);
    }catch (\Exception $e){

    }
     ?>
</div>
