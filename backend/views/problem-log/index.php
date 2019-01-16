<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProblemLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '问题件日志';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="problem-log-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'waybillId',
            'actionerId',
            'actioner_name',
            'datetime:datetime',
            'detail',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
