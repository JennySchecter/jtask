<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wechat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qq')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paperworkCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paperworkCode2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'balance')->textInput() ?>

    <?= $form->field($model, 'balance1')->textInput() ?>

    <?= $form->field($model, 'balance2')->textInput() ?>

    <?= $form->field($model, 'balance3')->textInput() ?>

    <?= $form->field($model, 'creditMoney')->textInput() ?>

    <?= $form->field($model, 'goodsName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goodsEnglish')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goodsCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goodsPrice')->textInput() ?>

    <?= $form->field($model, 'special')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contract')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payType')->textInput() ?>

    <?= $form->field($model, 'authSimple')->textInput() ?>

    <?= $form->field($model, 'authReturnApiError')->textInput() ?>

    <?= $form->field($model, 'apiKey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'esyUser')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'esyPass')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'storageId')->textInput() ?>

    <?= $form->field($model, 'department')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'groupid')->textInput() ?>

    <?= $form->field($model, 'isvip')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
