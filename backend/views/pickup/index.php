<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PickupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '取件记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pickup-index">

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
                    'label'=>'会员名',
                    'value' => function($model){
                        $userinfo = \backend\models\User::find()->where(['id'=>$model->userid])->one();
                        return $userinfo['name'];
                    }
                ],
                'username',
                'address',
                's_time:datetime',
                'e_time:datetime',
                'nums',
                'weight',
                'last_time:datetime',
                [
                    'format'=>'raw',
                    'label'=>'状态',
                    'attribute' => 'status',
                    'value' => function($model){
                        if($model->status==0){
                            return '待取件';
                        }elseif($model->status==1){
                            return '通知取件';
                        }elseif($model->status==2){
                            return '已取消';
                        }elseif($model->status==3){
                            return '已取件';
                        }else{
                            return '已超时';
                        }
                    }
                ],
                'c_time:datetime',
                //'dataColumnClass'=>"yii\grid\DataColumn"
                [
                        'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{update}',
                    'buttons' => [
                            'update' => function($url){
                                return Html::a('<i class="fa fa-fw fa-edit"></i>修改状态',$url,['class'=> 'btn btn-sm btn-success']);
                            }
                    ]
                ],
            ],
        ]);
    }catch(\Exception $e){

    }
     ?>
</div>
