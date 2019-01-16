<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProblemLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="problem-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'waybillId') ?>

    <?= $form->field($model, 'actionerId') ?>

    <?= $form->field($model, 'actioner_name') ?>

    <?= $form->field($model, 'datetime') ?>

    <?php // echo $form->field($model, 'detail') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
