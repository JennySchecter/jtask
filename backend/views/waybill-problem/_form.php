<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WaybillProblem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="waybill-problem-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'waybillId')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'dealcontent')->textarea() ?>
    <div class="form-group">
        <?= Html::submitButton('处理', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
