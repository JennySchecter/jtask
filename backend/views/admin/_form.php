<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true,'readonly'=>true]) ?>

    <?= $form->field($model, 'nickName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'datetime')->textInput(['value'=>date('Y-m-d H:i:s',$model->datetime),'readonly'=>true]) ?>

    <?= $form->field($model, 'status')->radioList([0=>'禁用',1=>'正常']) ?>

    <?= $form->field($model, 'password')->textInput(['placeholder'=>'不修改则留空']) ?>

    <?= $form->field($model, 'passwordC')->textInput(['placeholder'=>'不修改则留空']) ?>

    <?= $form->field($model, 'groupid')->dropDownList($glists) ?>

    <?= $form->field($model, 'kf_group')->dropDownList($klists) ?>
    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
