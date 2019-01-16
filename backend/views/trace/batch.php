<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use backend\assets\AppAsset;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\WaybillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//加载user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/bill.js');
$this->title = '运单批量追踪';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-index">
    <div class="box box-info">
        <!-- form start -->
            <?php
            $form = ActiveForm::begin([
                'action' => ['batch-trace'],
                'method' => 'post',
                'options'=>[
                        'class'=>'form-horizontal'
                ]
            ]);
            ?>
            <div class="box-body">
                <div class="form-group">
                    <label for="" class="col-xs-1 control-label">客户：</label>
                    <div class="col-xs-2">
                        <?php
                        echo $form->field($searchModel,'memberName')->label(false)->widget(Select2::className(),[
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
                    <label for="" class="col-xs-1 control-label">客户组别：</label>
                    <div class="col-xs-2">
                        <?php
                        echo $form->field($searchModel,'customGroup')->label(false)->widget(Select2::className(),[
                            'options' => ['placeholder' => '请选择...'],
                            'pluginOptions' => [
                                'placeholder' => 'search ...',
                                'allowClear' => true,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                                ],
                                'ajax' => [
                                    'url' => yii\helpers\Url::to(['/group/get-group-name']),
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(res) { return res.text; }'),
                                'templateSelection' => new JsExpression('function (res) { return res.text; }'),
                            ],
                        ]);
                        ?>                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-xs-1 control-label">订单号：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'orderNum')->label(false)->textInput(['placeholder'=>'订单号'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">运单号：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'codeNum')->label(false)->textInput(['placeholder'=>'运单号'])?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-xs-1 control-label">仓库：</label>
                    <div class="col-xs-2">
                        <?php
                        echo $form->field($searchModel,'storageId')->label(false)->widget(Select2::className(),[
                            'options' => ['placeholder' => '请选择...'],
                            'pluginOptions' => [
                                'placeholder' => 'search ...',
                                'allowClear' => true,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                                ],
                                'ajax' => [
                                    'url' => yii\helpers\Url::to(['/storage/search-storage']),
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
                    <label for="" class="col-xs-2 control-label">目的国家：</label>
                    <div class="col-xs-2">
                        <?php
                        echo $form->field($searchModel,'countryId')->label(false)->widget(Select2::className(),[
                            'options' => ['placeholder' => '请选择...'],
                            'pluginOptions' => [
                                'placeholder' => 'search ...',
                                'allowClear' => true,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                                ],
                                'ajax' => [
                                    'url' => yii\helpers\Url::to(['/country/search-country']),
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
                    <label for="" class="col-xs-1 control-label">父渠道：</label>
                    <div class="col-xs-2">
                        <?php
                        echo $form->field($searchModel,'channelParentId')->label(false)->widget(Select2::className(),[
                            'options' => ['placeholder' => '请选择...'],
                            'pluginOptions' => [
                                'placeholder' => 'search ...',
                                'allowClear' => true,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                                ],
                                'ajax' => [
                                    'url' => yii\helpers\Url::to(['/channel/get-parent-channel']),
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
                </div>
                <div class="form-group">
                    <label for="" class="col-xs-1 control-label">入库时间：</label>
                    <div class="col-xs-3">
                        <?php
                        echo $form->field($searchModel,'time_in_start')->label(false)->widget(DateTimePicker::className(),[
                            'options' => ['placeholder' => '请选择入库时间起'],
                            'language' => 'zh-CN',
                            'pluginOptions' => [
                                'autoclose' => true,
                            ]
                        ])
                        ?>
                        <?php
                        echo $form->field($searchModel,'time_in_end')->label(false)->widget(DateTimePicker::className(),[
                            'options' => ['placeholder' => '请选择入库时间止'],
                            'language' => 'zh-CN',
                            'pluginOptions' => [
                                'autoclose' => true,
                            ]
                        ])
                        ?>
                    </div>
                    <label for="" class="col-xs-1 control-label">出库时间：</label>
                    <div class="col-xs-3">
                        <?php
                        echo $form->field($searchModel,'time_out_start')->label(false)->widget(DateTimePicker::className(),[
                            'options' => ['placeholder' => '请选择出库时间起'],
                            'language' => 'zh-CN',
                            'pluginOptions' => [
                                'autoclose' => true,
                            ]
                        ])
                        ?>
                        <?php
                        echo $form->field($searchModel,'time_out_end')->label(false)->widget(DateTimePicker::className(),[
                            'options' => ['placeholder' => '请选择出库时间止'],
                            'language' => 'zh-CN',
                            'pluginOptions' => [
                                'autoclose' => true,
                            ]
                        ])
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-xs-1 control-label">航班号：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'flyNo')->label(false)->textInput(['placeholder'=> '航班号'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">包号：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'bagNo')->label(false)->textInput(['placeholder' => '包号'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">状态：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'status')->label(false)->dropDownList(Yii::$app->params['waybillStatus'])?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info">筛选</button>
                <?php
                if(Yii::$app->session->has('msg')){
                    echo '<span style="color: red">' . Yii::$app->session->getFlash('msg') . '</span>';
                }
                ?>
            </div>
            <?php ActiveForm::end();?>
            <!-- /.box-footer -->
    </div>

</div>


