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
/* @var $this yii\web\View */
/* @var $model backend\models\SurveyList */

$this->title = '修改工单 ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '调查工单', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="survey-list-update">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'it_id')->dropDownList(\backend\models\InvestigateType::dropDrownList()) ?>

    <?= $form->field($model, 'order_num')->textInput(['id'=>'onum']) ?>

    <?= $form->field($model, 'member_name')->textInput(['maxlength' => true,'id'=>'getname','readonly'=>true]) ?>

    <?= $form->field($model, 'dc_channel')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'create_user')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
