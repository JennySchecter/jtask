<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model backend\models\SurveyList */
//引入user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/user.js');
$this->title = '新建调查工单';
$this->params['breadcrumbs'][] = ['label' => '调查工单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-list-create">


    <?php $form = ActiveForm::begin(); ?>
    <div class="box">
        <div class="box-header center-block">
            <h3 align="center">新建调查工单</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-condensed table-bordered text-center">
                <tbody>
                    <tr>
                        <td>调查类型：</td>
                        <td><?= $form->field($model, 'it_id')->label(false)->dropDownList(\backend\models\InvestigateType::dropDrownList()) ?></td>
                        <td>调查编号：</td>
                        <td><?= $form->field($model,'dc_num')->label(false)->textInput(['readonly'=>true,'value'=>'DC'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8)])?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>订单编号：</td>
                        <td><?= $form->field($model, 'order_num')->label(false)->textInput(['id'=>'onum']) ?></td>
                        <td>客户名称：</td>
                        <td><?= $form->field($model, 'member_name')->label(false)->textInput(['maxlength' => true,'id'=>'getname','readonly'=>true]) ?></td>
                        <td>调查渠道：</td>
                        <td><?= $form->field($model, 'dc_channel')->label(false)->textInput(['id'=>'channel','readonly'=>true]) ?></td>
                    </tr>
                    <tr aria-colspan="2">
                        <td>情况描述：</td>
                        <td colspan="5">
                            <?= $form->field($model, 'description')->label(false)->textarea(['rows' => 6]) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>创建人</td>
                        <td><?= $form->field($model, 'create_user')->label(false)->textInput(['maxlength' => true,'value'=>yii::$app->user->getIdentity()->username]) ?></td>
                        <td>创建日期</td>
                        <td>默认为当前时间</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="form-group">
        <?= Html::submitButton("新建", ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
