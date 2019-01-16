<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PickupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '取件记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pickup-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    try{
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                'address',
                's_time:datetime',
                'e_time:datetime',
                'nums',
                'weight',
                'last_time:datetime',
                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value'=> function($model){
                            if($model->status==0){
                                return '待取件';
                            }elseif($model->status==1){
                                return '已取件';
                            }else{
                                return '已超时';
                            }
                    }
                ],
                'c_time:datetime',

                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    }catch(\Exception $e){

    }
     ?>
</div>
