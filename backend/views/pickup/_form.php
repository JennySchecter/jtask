<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Pickup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pickup-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userid')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true,'readonly'=>true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true,'readonly'=>true]) ?>

    <label class="control-label">开始时间</label>
    <input type="text" readonly value="<?=date('Y-m-d H:i:s',$model->s_time);?>" class="form-control"/>

    <label class="control-label">结束时间</label>
    <input type="text" readonly value="<?=date('Y-m-d H:i:s',$model->e_time);?>" class="form-control"/>

    <?= $form->field($model, 'nums')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'weight')->textInput(['readonly'=>true]) ?>

    <label class="control-label">最迟取件时间</label>
    <input type="text" readonly value="<?=date('Y-m-d H:i:s',$model->last_time);?>" class="form-control"/>

    <?= $form->field($model, 'status')->dropDownList([
            0=>'待取件',
            1=>'通知取件',
            2=>'已取消',
            3=>'已取件',
            4=>'已超时',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
