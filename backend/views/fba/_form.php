<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Fba */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fba-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codeNum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'orderNum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expressNum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'declareNum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'channelParentId')->dropDownList(\backend\models\Channel::dropDrownParent(),['id'=>'parent']) ?>

    <?php
    $getChildUrl = \yii\helpers\Url::to(['/channel/get-child-by-parent']);
    $js = <<<JS
    $(document).on('change','#parent',function() {
      var pid = $(this).val();
      $.get('{$getChildUrl}',{'pid':pid},function(data) {
        $('#child').empty();
        $('#child').append(data);
      })
    });
JS;
    $this->registerJs($js);
    ?>

    <?= $form->field($model, 'channelChildId')->dropDownList(\backend\models\Channel::dropDrownList(),['id'=>'child']) ?>

    <?= $form->field($model, 'consigneeId')->textInput() ?>

    <?= $form->field($model, 'storageId')->dropDownList(\backend\models\Storage::dropDrownList()) ?>

    <?= $form->field($model, 'countryId')->dropDownList(\backend\models\Country::dropDrownList()) ?>

    <?= $form->field($model, 'weightInput')->textInput() ?>

    <?= $form->field($model, 'weightOutput')->textInput() ?>

    <?= $form->field($model, 'weightVolume')->textInput() ?>

    <?= $form->field($model, 'volumeLength')->textInput() ?>

    <?= $form->field($model, 'volumeWidth')->textInput() ?>

    <?= $form->field($model, 'volumeHeight')->textInput() ?>

    <?= $form->field($model, 'declareValue')->textInput() ?>

    <?= $form->field($model, 'amountWaybill')->textInput() ?>

    <?= $form->field($model, 'amountPaied')->textInput() ?>

    <?= $form->field($model, 'amountSupplier')->textInput() ?>

    <?= $form->field($model, 'flyNo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bagNo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['waybillStatus']) ?>

    <?= $form->field($model, 'dataInvoice')->textarea(['rows' => 6,'readonly'=>true]) ?>

    <?= $form->field($model, 'dataLabel')->textarea(['rows' => 6,'readonly'=>true]) ?>

    <?= $form->field($model, 'dataError')->textarea(['rows' => 6,'readonly'=>true]) ?>

    <?= $form->field($model, 'dataSuccess')->textarea(['rows' => 6,'readonly'=>true]) ?>


    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
