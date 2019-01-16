<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProblemLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="problem-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'waybillId')->textInput() ?>

    <?= $form->field($model, 'actionerId')->textInput() ?>

    <?= $form->field($model, 'actioner_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'datetime')->textInput() ?>

    <?= $form->field($model, 'detail')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
