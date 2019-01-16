<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\InvestigateType */

$this->title = '更新调查类型: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '调查类型', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="investigate-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
