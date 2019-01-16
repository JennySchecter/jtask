<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\Waybill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="waybill-form">

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

    <?= $form->field($model, 'storageId')->dropDownList(\backend\models\Storage::dropDrownList()) ?>

    <?= $form->field($model, 'countryId')->dropDownList(\backend\models\Country::dropDrownList()) ?>

    <?= $form->field($model, 'consigneeName')->textInput(['value'=> $model['waybillConsignee']['consigneeName']])?>

    <?= $form->field($model, 'consigneeTel')->textInput(['value'=> $model['waybillConsignee']['consigneeTel']])?>

    <?= $form->field($model, 'consigneeZip')->textInput(['value'=> $model['waybillConsignee']['consigneeZip']])?>

    <?= $form->field($model, 'consigneeState')->textInput(['value'=> $model['waybillConsignee']['consigneeState']])?>

    <?= $form->field($model, 'consigneeCity')->textInput(['value'=> $model['waybillConsignee']['consigneeCity']])?>

    <?= $form->field($model, 'consigneeCounty')->textInput(['value'=> $model['waybillConsignee']['consigneeCounty']])?>

    <?= $form->field($model, 'consigneeAddress1')->label('详细地址')->textInput(['value'=> $model['waybillConsignee']['consigneeAddress1']])?>

    <?= $form->field($model, 'weightInput')->textInput() ?>

    <?= $form->field($model, 'weightOutput')->textInput() ?>

    <?= $form->field($model, 'weightVolume')->textInput() ?>

    <?= $form->field($model, 'volumeLength')->textInput() ?>

    <?= $form->field($model, 'volumeWidth')->textInput() ?>

    <?= $form->field($model, 'volumeHeight')->textInput() ?>

    <?= $form->field($model, 'declareValue')->textInput() ?>

    <?= $form->field($model, 'overWeightOut')->dropDownList([0=>'不发货',1=>'发货']) ?>

    <?= $form->field($model, 'valueInsured')->dropDownList([0=>'不保价',1=>'保价']) ?>

    <?= $form->field($model, 'flyNo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bagNo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remarkSpecial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remarkMember')->textInput(['maxlength' => true,'readonly'=>true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?=
        $form->field($model, 'status')->dropDownList(array_slice(Yii::$app->params['waybillStatus'],1,count(Yii::$app->params['waybillStatus'])-1,true),[
            'options'=>[
                    $model['waybillStatus']['status']=>[
                            'Selected'=>true,
                    ],
                ],
        ])
    ?>

    <?= $form->field($model, 'dataInvoice')->textarea(['rows' => 6,'readonly'=>true]) ?>

    <?= $form->field($model, 'dataLabel')->textarea(['rows' => 6,'readonly'=>true]) ?>

    <?= $form->field($model, 'dataError')->textarea(['rows' => 6,'readonly'=>true]) ?>

    <?= $form->field($model, 'dataSuccess')->textarea(['rows' => 6,'readonly'=>true]) ?>

    <?= $form->field($model, 'epl')->textarea(['rows' => 6,'readonly'=>true]) ?>

    <?= $form->field($model, 'waybillPdfUrl')->textInput(['maxlength' => true,'readonly'=>true]) ?>

    <?= $form->field($model, 'invoicePdfUrl')->textInput(['maxlength' => true,'readonly'=>true]) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        <?php
        if(Yii::$app->session->has('msg')){
            echo '<span style="color: darkgreen">'.Yii::$app->session->getFlash('msg').'</span>';
        }
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
