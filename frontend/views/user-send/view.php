<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\UserSend */

$this->title = '查看';
$this->params['breadcrumbs'][] = ['label' => '我要寄件', 'url' => ['index']];
$this->params['breadcrumbs'][] =  $model->id;
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
                    'format'=>'raw',
                    'label'=>'状态',
                    'attribute'=>'status',
                    'value'=> function($model){
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
                'up_time:datetime',
                'handleId',
                'memremark',
                'remark'
            ],
        ]) ;
    }catch(\Exception $e){

    }
    ?>

</div>
