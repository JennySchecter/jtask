<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\UserSendSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-send-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'company') ?>

    <?= $form->field($model, 'expressNum') ?>

    <?php // echo $form->field($model, 'sendername') ?>

    <?php // echo $form->field($model, 'sendermobile') ?>

    <?php // echo $form->field($model, 'nums') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'item') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'c_time') ?>

    <?php // echo $form->field($model, 'up_time') ?>

    <?php // echo $form->field($model, 'handleId') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
