<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\InvestigateType */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '调查类型', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="investigate-type-view">

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定删除此调查类型?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    try{
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'dc_name',
                'create_user',
                'c_time:datetime',
            ],
        ]);
}catch (\Exception $e) {

    }?>

</div>
