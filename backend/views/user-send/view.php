<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSend */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '客户寄件', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-send-view">

    <?php
    try{
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'userid',
                'username',
                'company',
                'expressNum',
                'sendername',
                'sendermobile',
                'nums',
                'weight',
                'item',
                [
                    'format' => 'raw',
                    'attribute'=> 'status',
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
                    'format'=>'raw',
                    'attribute'=>'up_time',
                    'value' => function($model){
                            return $model->up_time!='' ? date('Y-m-d H:i:s',$model->up_time):'';
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'handleId',
                    'value' => function($model){
                            return $model->handleId!='' ? $model->handleId:'';
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'memremark',
                    'value' => function($model){
                        return $model->memremark!='' ? $model->memremark:'';
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'remark',
                    'value' => function($model){
                        return $model->remark!='' ? $model->remark:'';
                    }
                ],
            ],
        ]);
    }catch(\Exception $e){

    }
     ?>

</div>
