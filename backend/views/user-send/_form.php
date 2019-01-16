<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSend */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-send-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userid')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'username')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'company')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'expressNum')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'sendername')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'sendermobile')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'nums')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'weight')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'item')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'memremark')->textInput(['readonly'=>true]) ?>

    <?= $form->field($model, 'remark')->textInput(['placeholder' => '说明设为问题件原因']) ?>

    <div class="form-group">
        <?= Html::submitButton('确认', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
