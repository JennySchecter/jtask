<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SurveyListSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="survey-list-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'it_id') ?>

    <?= $form->field($model, 'dc_num') ?>

    <?= $form->field($model, 'order_num') ?>

    <?= $form->field($model, 'member_name') ?>

    <?php // echo $form->field($model, 'dc_channel') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'create_user') ?>

    <?php // echo $form->field($model, 'c_time') ?>

    <?php // echo $form->field($model, 'isadd') ?>

    <?php // echo $form->field($model, 'feedback') ?>

    <?php // echo $form->field($model, 'deal_user') ?>

    <?php // echo $form->field($model, 'deal_time') ?>

    <?php // echo $form->field($model, 'next_time') ?>

    <?php // echo $form->field($model, 'deal_num') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'dc_result') ?>

    <?php // echo $form->field($model, 'undertake') ?>

    <?php // echo $form->field($model, 'undertake_name') ?>

    <?php // echo $form->field($model, 'compensate_money') ?>

    <?php // echo $form->field($model, 'file_user') ?>

    <?php // echo $form->field($model, 'file_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
