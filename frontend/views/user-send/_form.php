<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\UserSend */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-send-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expressNum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sendername')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sendermobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nums')->textInput() ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'item')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'memremark')->textInput() ?>

    <?= $form->field($model, 'remark')->textInput(['readonly'=>true]) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
