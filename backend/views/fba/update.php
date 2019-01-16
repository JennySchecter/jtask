<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Fba */

$this->title = '修改Fba';
$this->params['breadcrumbs'][] = ['label' => 'Fba列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="fba-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
