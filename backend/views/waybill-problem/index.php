<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\WaybillProblemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '问题件列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-problem-index">

    <table class="table">
        <tr>
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get'
            ])?>
            <td><?=$form->field($searchModel,'waybillId')->textInput()?></td>
            <td><?=$form->field($searchModel,'deal_status')->dropDownList([0=>'未处理',1=>'处理中',2=>'处理完成'])?></td>
            <td>
                <?=$form->field($searchModel,'s_time')->label('问题件创建时间起')->widget(DateTimePicker::classname(),[
                    'options' => ['placeholder' => ''],
                    'language' =>'zh-CN',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ])?>
            </td>
            <td>
                <?=$form->field($searchModel,'e_time')->label('问题件创建时间止')->widget(DateTimePicker::classname(),[
                    'options' => ['placeholder' => ''],
                    'language' =>'zh-CN',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ])?>
            </td>
            <td><?=$form->field($searchModel,'codeNum')->label('运单号')->textInput()?></td>
            <td><?=$form->field($searchModel,'orderNum')->label('订单号')->textInput()?></td>
            <td><?=$form->field($searchModel,'expressNum')->label('转单号')->textInput()?></td>
        </tr>
        <tr>
            <td>
                <?= Html::submitButton('查找',['class'=>'btn btn-primary'])?>
            </td>
            <td>
                <?= Html::a('导出数据',['export'],['class'=>'btn btn-default'])?>
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
                //['class' => 'yii\grid\SerialColumn'],

                'id',
                'waybillId',
                'waybill.codeNum',
                'waybill.orderNum',
                'waybill.expressNum',
                [
                        'attribute' => 'deal_status',
                    'label' => '状态',
                    'format' => 'raw',
                    'value' =>function($model){
                        if($model->deal_status==0){
                            return '未处理';
                        }elseif($model->deal_status==1){
                            return '处理中';
                        }else{
                            return '处理完成';
                        }
                    }
                ],
                'c_time:datetime',
                //'up_time:datetime',
                'remark',
                'create_user',

                ['class' => 'yii\grid\ActionColumn',
                    'header'=> '操作',
                    'template'=> '{deal}',
                    'buttons' => [
                      'deal' => function($url,$model,$key){
                        return Html::a('<button class="btn btn-xs btn-info">处理</button>',$url);
                      }
                    ],
                ],
            ],
        ]);
    } catch(\Exception $e){

    }?>
</div>
