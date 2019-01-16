<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\AppAsset;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\WaybillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//加载user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/user.js');
$this->title = '运单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-index">
    <table class="table">
        <tr>
            <?php $form = ActiveForm::begin([
                'action' => ['overtime'],
                'method' => 'get'
            ])?>
            <td><?=$form->field($searchModel,'time_in_start')->label('入库时间起')->widget(DateTimePicker::className(),[
                    'options' => ['placeholder' => ''],
                    'language' =>'zh-CN',
                    'pluginOptions' => [
                        'autoClose' => true,
                        'todayHighlight' => true,
                    ]
                ])?></td>
            <td><?=$form->field($searchModel,'time_in_end')->label('入库时间止')->widget(DateTimePicker::className(),[
                    'options' => ['placeholder' => ''],
                    'language' =>'zh-CN',
                    'pluginOptions' => [
                        'autoClose' => true,
                        'todayHighlight' => true,
                    ]
                ])?></td>
            <td><?php
                echo $form->field($searchModel,'memberName')->widget(Select2::className(),[
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
                ?></td>
            <td><?=$form->field($searchModel,'codeNum')->label('运单号')->textInput()?></td>

        </tr>
        <tr>
            <td><?=$form->field($searchModel,'orderNum')->label('订单号')->textInput()?></td>
            <td><?=$form->field($searchModel,'expressNum')->label('转单号')->textInput()?></td>
            <td>
                <?php
                echo $form->field($searchModel,'channelParentId')->widget(Select2::className(),[
                    'options' => ['placeholder' => '请选择...'],
                    'pluginOptions' => [
                        'placeholder' => 'search ...',
                        'allowClear' => true,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                        ],
                        'ajax' => [
                            'url' => yii\helpers\Url::to(['/survey-list/search-channel']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(res) { return res.text; }'),
                        'templateSelection' => new JsExpression('function (res) { return res.text; }'),
                    ],
                ]);
                ?>
            </td>
            <td>
                <?php
                echo $form->field($searchModel,'channelChildId')->widget(Select2::className(),[
                    'options' => ['placeholder' => '请选择...'],
                    'pluginOptions' => [
                        'placeholder' => 'search ...',
                        'allowClear' => true,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                        ],
                        'ajax' => [
                            'url' => yii\helpers\Url::to(['/survey-list/search-channel']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(res) { return res.text; }'),
                        'templateSelection' => new JsExpression('function (res) { return res.text; }'),
                    ],
                ]);
                ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= Html::submitButton('查找',['class'=>'btn btn-primary'])?>
            </td>
            <?php ActiveForm::end();?>
        </tr>
    </table>
    <?php
    try{
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                'id',
                'codeNum',
                'orderNum',
                'expressNum',
                'memberName',
                'waybillActioner.timeIn:datetime',
                [
                    'attribute' => 'status',
                    'label' => '运单状态',
                    'value' => function($model){
                        return \backend\models\Waybill::getStatus($model->id);
                    }
                ],

//                [
//                    'class' => 'yii\grid\ActionColumn',
//                ],
            ],
        ]);
    } catch(\Exception $e){

    }?>
</div>
