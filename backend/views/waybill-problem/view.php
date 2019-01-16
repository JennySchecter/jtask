<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\WaybillProblem */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Waybill Problems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-problem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定删除吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'waybillId',
            'deal_status',
            'c_time:datetime',
            'up_time:datetime',
            'remark',
            'create_user',
            'create_id',
        ],
    ]) ?>

</div>
