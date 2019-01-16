<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\WaybillProblem */

$this->title = '更新问题件运单: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '问题件运单', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="waybill-problem-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
