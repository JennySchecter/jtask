<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\assets\AppAsset;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\SurveyList */
//引入user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/user.js');
/* @var $this yii\web\View */
/* @var $model backend\models\SurveyList */

$this->title = '处理工单 ' . $model->id;
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

    <?php
    if($model->deal_num > 0){
        $fb_arr = explode(';',$model->feedback);
        $str = '<div class="form-group">
        <label>处理反馈：</label>
        <div>';
        $i = 1;
        foreach ($fb_arr as $k=>$v){
            $str .=  $i.'.'.$v.'<br/>';
            $i++;
        }
        echo $str.'</div></div>';
    }
    ?>

    <?= $form->field($model, 'dealContent')->textInput() ?>

    <?=$form->field($model,'repay_time')->label('下次联系日期')->widget(DateTimePicker::classname(),[
        'options' => ['placeholder' => ''],
        'language' =>'zh-CN',
        'pluginOptions' => [
            'autoClose' => true,
            'todayHighlight' => true,
        ],
    ])?>


    <div class="form-group">
        <?= Html::submitButton('处理', ['class' => 'btn btn-success']) ?>
        <?php if($model->deal_num >=1){?>
            <a href="<?=yii\helpers\Url::to(['beforefile','id'=>$model->id])?>" class="btn btn-primary">归档并理赔</a>
            <a href="<?=yii\helpers\Url::to(['file','id'=>$model->id])?>" class="btn btn-primary">直接归档</a>
        <?php }?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
