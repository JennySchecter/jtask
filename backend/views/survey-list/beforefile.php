<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\assets\AppAsset;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model backend\models\SurveyList */
//引入user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/user.js');
/* @var $this yii\web\View */
/* @var $model backend\models\SurveyList */

$this->title = '处理结果预归档 ';
$this->params['breadcrumbs'][] = ['label' => '调查工单', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '处理结果预归档';
?>
<div class="survey-list-update">

    <form action="<?=yii\helpers\Url::to(['/survey-list/compensate'])?>" method="post" onsubmit="return compensate();">
        <div class="form-group">
            <label for="dc_result" class="control-label">调查结果</label><br/>
            <input type="radio" name="dc_result" class="radio-inline apologise" value="1"/>致歉
            <input type="radio" name="dc_result" class="radio-inline compensate" value="2"/>赔偿
        </div>
        <input name="_csrf-backend" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="s_id" type="hidden" value="<?=$model->id?>">
        <div class="form-group pc_step1" style="display: none">
            <label class="control-label">赔偿责任划分</label><br/>
            <input type="checkbox" name="pc_type[]" class="checkbox-inline office" value="1"/>官方
            <input type="checkbox" name="pc_type[]" class="checkbox-inline company" value="2"/>公司
        </div>

        <div class="form-group office_pc" style="display: none">
            <label class="control-label">官方承担姓名</label><br/>
            <input type="text" name="office_name" class="form-control"/>
            <label class="control-label">官方承担金额</label><br/>
            <input type="number" name="office_money" class="form-control"/>
        </div>

        <div class="form-group company_pc" style="display: none">
            <label class="control-label">公司承担部门</label><br/>
            <?php
                try{
                    echo Select2::widget([
                        'name' => 'departments',
                        'id' => 'departments',
                        'options' => ['multiple' => true, 'placeholder' => '请选择...'],
                        'pluginOptions' => [
                            'placeholder' => 'search ...',
                            'allowClear' => true,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                            ],
                            'ajax' => [
                                'url' => yii\helpers\Url::to(['/survey-list/search-department']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(res) { return res.text; }'),
                            'templateSelection' => new JsExpression('function (res) { return res.text; }'),
                        ],
                    ]);
                }catch (\Exception $e){

                }
            ?>
            <label class="control-label">公司承担金额</label><br/>
            <input type="number" name="company_money" class="form-control company_money"/>

            <label class="control-label">承担员工</label><br/>
            <?php
            try{
                echo Select2::widget([
                    'name' => 'company_name',
                    'id' => 'staff',
                    'options' => ['multiple' => true, 'placeholder' => '请选择...'],
                    'pluginOptions' => [
                        'placeholder' => 'search ...',
                        'allowClear' => true,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                        ],
                        'ajax' => [
                            'url' => yii\helpers\Url::to(['/survey-list/search-staff']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(res) { return res.text; }'),
                        'templateSelection' => new JsExpression('function (res) { return res.text; }'),
                    ],
                ]);
            }catch (\Exception $e){

            }
            ?>
        </div>

        <div class="form-group">
            <label class="control-label">归档意见</label><br/>
            <input type="text" name="file_content" class="form-control"/>
        </div>
    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
    </div>
    </form>
</div>
