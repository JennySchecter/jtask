<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FbaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fba-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
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

    <?php // echo $form->field($model, 'consigneeId') ?>

    <?php // echo $form->field($model, 'storageId') ?>

    <?php // echo $form->field($model, 'countryId') ?>

    <?php // echo $form->field($model, 'weightInput') ?>

    <?php // echo $form->field($model, 'weightOutput') ?>

    <?php // echo $form->field($model, 'weightVolume') ?>

    <?php // echo $form->field($model, 'volumeLength') ?>

    <?php // echo $form->field($model, 'volumeWidth') ?>

    <?php // echo $form->field($model, 'volumeHeight') ?>

    <?php // echo $form->field($model, 'declareValue') ?>

    <?php // echo $form->field($model, 'amountWaybill') ?>

    <?php // echo $form->field($model, 'amountPaied') ?>

    <?php // echo $form->field($model, 'amountSupplier') ?>

    <?php // echo $form->field($model, 'flyNo') ?>

    <?php // echo $form->field($model, 'bagNo') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'userCreate') ?>

    <?php // echo $form->field($model, 'timeCreate') ?>

    <?php // echo $form->field($model, 'userIn') ?>

    <?php // echo $form->field($model, 'timeIn') ?>

    <?php // echo $form->field($model, 'userUpdate') ?>

    <?php // echo $form->field($model, 'timeUpdate') ?>

    <?php // echo $form->field($model, 'userExpress') ?>

    <?php // echo $form->field($model, 'timeExpress') ?>

    <?php // echo $form->field($model, 'userModify') ?>

    <?php // echo $form->field($model, 'timeModify') ?>

    <?php // echo $form->field($model, 'userOut') ?>

    <?php // echo $form->field($model, 'timeOut') ?>

    <?php // echo $form->field($model, 'timeSign') ?>

    <?php // echo $form->field($model, 'userMerge') ?>

    <?php // echo $form->field($model, 'timeMerge') ?>

    <?php // echo $form->field($model, 'pickUserId') ?>

    <?php // echo $form->field($model, 'pickUserName') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'statusAbnormalDomestic') ?>

    <?php // echo $form->field($model, 'statusAbnormalForeign') ?>

    <?php // echo $form->field($model, 'statusAbnormalRemark') ?>

    <?php // echo $form->field($model, 'financeCheck') ?>

    <?php // echo $form->field($model, 'financeWriteoff') ?>

    <?php // echo $form->field($model, 'financeWriteoffNo') ?>

    <?php // echo $form->field($model, 'financeWriteoffMoney') ?>

    <?php // echo $form->field($model, 'recycle') ?>

    <?php // echo $form->field($model, 'dataInvoice') ?>

    <?php // echo $form->field($model, 'dataLabel') ?>

    <?php // echo $form->field($model, 'dataError') ?>

    <?php // echo $form->field($model, 'dataSuccess') ?>

    <?php // echo $form->field($model, 'overWeightOut') ?>

    <?php // echo $form->field($model, 'valueInsured') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
