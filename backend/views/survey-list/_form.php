<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SurveyList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="survey-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'it_id')->textInput() ?>

    <?= $form->field($model, 'dc_num')->textInput() ?>

    <?= $form->field($model, 'order_num')->textInput() ?>

    <?= $form->field($model, 'member_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dc_channel')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'create_user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'c_time')->textInput() ?>

    <?= $form->field($model, 'isadd')->textInput() ?>

    <?= $form->field($model, 'feedback')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'deal_user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deal_time')->textInput() ?>

    <?= $form->field($model, 'next_time')->textInput() ?>

    <?= $form->field($model, 'deal_num')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'dc_result')->textInput() ?>

    <?= $form->field($model, 'undertake')->textInput() ?>

    <?= $form->field($model, 'undertake_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'compensate_money')->textInput() ?>

    <?= $form->field($model, 'file_user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
