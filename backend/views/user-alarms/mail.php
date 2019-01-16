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
        'action' => ['mail'],
        'method' => 'post'
    ])?>
        <div class="box-body">
            <div class="form-group">
                <?php
                echo $form->field($model,'userId')->widget(Select2::className(),[
                    'options' => ['placeholder' => '请选择...'],
                    'pluginOptions' => [
                        'placeholder' => 'search ...',
                        'allowClear' => true,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                        ],
                        'ajax' => [
                            'url' => yii\helpers\Url::to(['/survey-list/search-user']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(res) { return res.text; }'),
                        'templateSelection' => new JsExpression('function (res) { return res.text; }'),
                    ],
                ]);
                ?>
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
