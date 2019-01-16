<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WaybillSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="waybill-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'codeNum') ?>

    <?= $form->field($model, 'orderNum') ?>

    <?= $form->field($model, 'expressNum') ?>

    <?= $form->field($model, 'declareNum') ?>

    <?php // echo $form->field($model, 'memberId') ?>

    <?php // echo $form->field($model, 'memberName') ?>

    <?php // echo $form->field($model, 'memberCode') ?>

    <?php // echo $form->field($model, 'channelParentId') ?>

    <?php // echo $form->field($model, 'channelChildId') ?>

    <?php // echo $form->field($model, 'storageId') ?>

    <?php // echo $form->field($model, 'countryId') ?>

    <?php // echo $form->field($model, 'consigneeId') ?>

    <?php // echo $form->field($model, 'actionerId') ?>

    <?php // echo $form->field($model, 'financeId') ?>

    <?php // echo $form->field($model, 'statusId') ?>

    <?php // echo $form->field($model, 'weightInput') ?>

    <?php // echo $form->field($model, 'timeIn') ?>

    <?php // echo $form->field($model, 'weightOutput') ?>

    <?php // echo $form->field($model, 'weightVolume') ?>

    <?php // echo $form->field($model, 'volumeLength') ?>

    <?php // echo $form->field($model, 'volumeWidth') ?>

    <?php // echo $form->field($model, 'volumeHeight') ?>

    <?php // echo $form->field($model, 'declareValue') ?>

    <?php // echo $form->field($model, 'overWeightOut') ?>

    <?php // echo $form->field($model, 'valueInsured') ?>

    <?php // echo $form->field($model, 'flyNo') ?>

    <?php // echo $form->field($model, 'bagNo') ?>

    <?php // echo $form->field($model, 'remarkSpecial') ?>

    <?php // echo $form->field($model, 'remarkMember') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'dataInvoice') ?>

    <?php // echo $form->field($model, 'dataLabel') ?>

    <?php // echo $form->field($model, 'dataError') ?>

    <?php // echo $form->field($model, 'dataSuccess') ?>

    <?php // echo $form->field($model, 'epl') ?>

    <?php // echo $form->field($model, 'waybillPdfUrl') ?>

    <?php // echo $form->field($model, 'invoicePdfUrl') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
