<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InvestigateTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '调查类型';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="investigate-type-index">

    <p>
        <?= Html::a('新增调查类型', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    try{
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [

                'id',
                'dc_name',
                'create_user',
                'c_time:datetime',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
    } catch (\Exception $e){

    }?>
</div>
