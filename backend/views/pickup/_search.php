<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PickupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pickup-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'address') ?>

    <?= $form->field($model, 's_time') ?>

    <?php // echo $form->field($model, 'e_time') ?>

    <?php // echo $form->field($model, 'nums') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'last_time') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'c_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
