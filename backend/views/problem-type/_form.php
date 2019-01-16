<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProblemType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="problem-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'probdesc')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
