<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\web\JsExpression;
$this->title = '发送站内信';
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"></h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <?php $form = ActiveForm::begin([
        'action' => ['problem-mail'],
        'method' => 'post'
    ])?>
        <div class="box-body">
            <div class="form-group">
                <?=$form->field($model,'userId')->hiddenInput(['value'=>$waybill['memberId']])?>
                <input type="text" value="<?=$waybill['memberName']?>" readonly class="form-control"/>
                <input type="hidden" value="<?=$waybill['id']?>" name="id" class="form-control"/>
            </div>
            <div class="form-group">
                <?=$form->field($model,'subject')->textInput()?>
            </div>
            <div class="form-group">
                <?=$form->field($model,'content')->textarea()?>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?=Html::submitButton('发送',['class' => 'btn btn-primary'])?>
            <?php
            if(Yii::$app->session->has('msg')){
                echo '<span style="color: #00a157">'.Yii::$app->session->getFlash('msg').'</span>';
            }
            ?>
        </div>
    <?php ActiveForm::end();?>
</div>
