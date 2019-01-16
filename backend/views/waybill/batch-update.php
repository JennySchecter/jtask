<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use backend\assets\AppAsset;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\WaybillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//加载user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/bill.js');
$this->title = '批量修改运单信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-index">
    <div class="box box-info">
        <?php
        $form = ActiveForm::begin([
            'action' => ['batch-update'],
            'method' => 'post',
            'options' => ['enctype' => 'multipart/form-data'],
        ]);
        ?>
        <div class="box-body">
            <div class="form-group">
                <?=$form->field($model,'file')->fileInput()?>
                <p class="help-block">
                    根据提供的excel模版将其中的参考信息删除，然后输入正确的信息。
                    选择您编辑好的excel文件（选择文件后系统将自动上传），文件大小不超过2M。
                </p>
                <p>注意：EXCEL中的所有数据请严格与系统中的数据匹配，否则不能导入</p>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?=Html::submitButton('批量修改',['class'=>'btn btn-info'])?>
            <?php
            if(Yii::$app->session->has('msg')){
                echo '<span style="color: #00a157">'.Yii::$app->session->getFlash('msg').'</span>';
            }
            ?>
        </div>
        <?php ActiveForm::end();?>
        <!-- /.box-footer -->
    </div>
</div>


