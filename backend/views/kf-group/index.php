<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\KfGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客服分组列表';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="kf-group-index">


    <p>
        <?= Html::a('添加分组', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    try{
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [

                'id',
                'groupname',
                'c_time:datetime',
                'c_user',

                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    }catch (\Exception $e){

    } ?>
</div>
