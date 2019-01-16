<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//引入日期插件
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model frontend\models\Pickup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pickup-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 's_time')->widget(DateTimePicker::classname(),[
        'options' => ['placeholder' => ''],
        'language' =>'zh-CN',
        'pluginOptions' => [
                'autoclose' => true,
                'todayHighlight' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'e_time')->widget(DateTimePicker::classname(),[
        'options' => ['placeholder' => ''],
        'language' =>'zh-CN',
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
        ]
    ]) ?>

    <?= $form->field($model, 'nums')->textInput() ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'last_time')->widget(DateTimePicker::classname(),[
        'options' => ['placeholder' => ''],
        'language' =>'zh-CN',
        'pluginOptions' => [
            'autoclose' => true,
            'todayHighlight' => true,
        ]
    ]) ?>


    <div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
