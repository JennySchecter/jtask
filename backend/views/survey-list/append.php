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

$this->title = '追加调查工单 ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '调查工单', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="survey-list-update">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'it_id')->dropDownList(\backend\models\InvestigateType::dropDrownList(),['disabled' =>true]) ?>

    <?= $form->field($model, 'order_num')->textInput(['disabled' =>true]) ?>

    <?= $form->field($model, 'member_name')->textInput(['maxlength' => true,'id'=>'getname','disabled' =>true]) ?>

    <?= $form->field($model, 'dc_channel')->textInput(['disabled' =>true]) ?>

    <div class="form-group">
        <label>情况描述</label>
        <div>
            <?php
            $ds_arr = explode(';',$model->description);
            $str = '';
            $i = 1;
            foreach ($ds_arr as $k=>$v){
                $str .=  $i.'.'.$v.'<br/>';
                $i++;
            }
            echo $str;
            ?>
        </div>

    </div>

    <?= $form->field($model, 'create_user')->textInput(['disabled' =>true]) ?>

    <?= $form->field($model, 'deal_num')->textInput(['disabled' =>true]) ?>

    <?= $form->field($model, 'appendContent')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
