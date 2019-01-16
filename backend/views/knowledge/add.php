<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->title = '新建知识库内容';
$this->params['breadcrumbs'][] = ['label' => '知识库列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"></h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <?php $form = ActiveForm::begin([
        'action' => ['add'],
        'method' => 'post',
    ])?>
    <div class="box-body">
        <div class="form-group">
            <?=$form->field($model,'subject')->textInput()?>
        </div>
        <div class="form-group">
            <?=$form->field($upload,'knowledgeFile')->fileInput()?>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <?=Html::submitButton('保存',['class' => 'btn btn-primary'])?>
        <?php
        if(Yii::$app->session->has('msg')){
            echo '<span style="color: #00a157">'.Yii::$app->session->getFlash('msg').'</span>';
        }
        ?>
    </div>
    <?php ActiveForm::end();?>
</div>
