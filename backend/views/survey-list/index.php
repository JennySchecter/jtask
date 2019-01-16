<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;
use backend\assets\AppAsset;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\SurveyListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//引入user.js文件
AppAsset::register($this);
AppAsset::addScript($this,yii::$app->request->baseUrl.'/js/user.js');
$this->title = '调查工单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-list-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新建调查工单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table">
        <tr>
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get'
            ])?>
            <td>
                <?=$form->field($searchModel,'s_time')->label('创建时间起')->widget(DateTimePicker::classname(),[
                    'options' => ['placeholder' => ''],
                    'language' =>'zh-CN',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ])?>
            </td>
            <td>
                <?=$form->field($searchModel,'e_time')->label('创建时间止')->widget(DateTimePicker::classname(),[
                    'options' => ['placeholder' => ''],
                    'language' =>'zh-CN',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ])?>
            </td>
            <td>
                <?=$form->field($searchModel,'ns_time')->label('下次联系时间起')->widget(DateTimePicker::classname(),[
                    'options' => ['placeholder' => ''],
                    'language' =>'zh-CN',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ])?>
            </td>
            <td>
                <?=$form->field($searchModel,'ne_time')->label('下次联系时间止')->widget(DateTimePicker::classname(),[
                    'options' => ['placeholder' => ''],
                    'language' =>'zh-CN',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ]
                ])?>
            </td>
        </tr>
        <tr>
            <td><?=$form->field($searchModel,'status')->label('状态')->dropDownList([
                    null => '全部',
                    0=>'新建工单',
                    1=>'处理中',
                    2=>'归档',
                ])?></td>
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
            <td>
                <?php
                echo $form->field($searchModel,'member_name')->widget(Select2::className(),[
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
            </td>
        </tr>
        <tr>
            <td>
                <?= Html::submitButton('查找',['class'=>'btn btn-primary'])?>
            </td>
            <?php ActiveForm::end();?>
        </tr>
    </table>
    <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
        <thead>
        <tr role="row">
            <th>ID</th>
            <th>调查类型</th>
            <th>调查编号</th>
            <th>订单编号</th>
            <th>客户名称</th>
            <th>调查渠道</th>
            <th>创建日期</th>
            <th>状态</th>
            <th>审核进度</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lists as $list):?>
            <tr role="row" class="even">
                <td><?php
                    if($list['isadd']==1){
                        echo $list['id'].'<span class="label label-danger">已追加</span>';
                    }else{
                        echo $list['id'];
                    }
                    ?>
                </td>
                <td><?php
                    $type = \backend\models\InvestigateType::find()->where(['id'=>$list['it_id']])->one();
                    echo $type['dc_name'];
                    //$list['it_id']
                    ?>
                </td>
                <td>
                    <?php
                        if($list['overtime']!=0){
                            echo '<span class="bg-red">'.$list['dc_num'].'</span>';
                        }else{
                            echo $list['dc_num'];
                        }
                    ?>
                </td>
                <td><?=$list['order_num']?></td>
                <td><?=$list['member_name']?></td>
                <td><?=$list['dc_channel']?></td>
                <td><?=date('Y-m-d H:i:s',$list['c_time'])?></td>
                <td><?php
                    switch ($list['status']){
                        case 0:
                            echo '新建工单';break;
                        case 1:
                            echo '处理中';break;
                        default:
                            echo '已归档';break;
                    }
                    ?></td>
                <td>
                    <?php
                        if($list['audit'] == 0){
                            echo '<span class="label label-warning">未审核</span>';
                        }elseif($list['audit'] == 21 && ($list['office_money']+$list['company_money']) <=400){
                            echo '<span class="label label-success">审核通过</span>';
                        }elseif($list['audit'] == 31 && ($list['office_money']+$list['company_money']) <=1000){
                            echo '<span class="label label-success">审核通过</span>';
                        }elseif($list['audit'] == 41){
                            echo '<span class="label label-success">审核通过</span>';
                        }else{
                            echo '<span class="label bg-aqua">审核中</span>';
                        }
                        echo '<a href="'. yii\helpers\Url::to(['/survey-list/check-progress','sid'=>$list['id']]) .'" class="btn btn-xs">查看进度</a>';
                    ?>
                </td>
                <td>
                    <a href="<?=yii\helpers\Url::to(['survey-list/view','id'=>$list['id']])?>" class="btn btn-xs btn-primary">查看</a>
                    <?php if($list['status']==0){?>
                    <a href="<?=yii\helpers\Url::to(['survey-list/update','id'=>$list['id']])?>" class="btn btn-xs btn-success">修改</a>
                    <a href="<?=yii\helpers\Url::to(['survey-list/dealwith','id'=>$list['id']])?>" class="btn btn-xs btn-default">处理</a>
                    <?php }elseif ($list['status']==1){?>
                    <a href="<?=yii\helpers\Url::to(['survey-list/append','id'=>$list['id']])?>" class="btn btn-xs btn-success">追加</a>
                    <a href="<?=yii\helpers\Url::to(['survey-list/dealwith','id'=>$list['id']])?>" class="btn btn-xs btn-default">处理</a>
                    <?php }else{
                        if($list['audit'] == 0){
                            //未审核则客服经理审核
                            echo Html::a('客户经理审核',\yii\helpers\Url::to(['/survey-list/account-manager-audit','id'=>$list['id']]),['class'=>'btn btn-xs bg-orange']);
                        }elseif($list['audit'] == 11){
                            //财务经理审核
                            echo Html::a('财务经理审核',\yii\helpers\Url::to(['/survey-list/finance-manager-audit','id'=>$list['id']]),['class'=>'btn btn-xs bg-orange']);
                        }elseif($list['audit'] == 21 && ($list['office_money']+$list['company_money']) > 400){
                            //若金额超过400则分管副总审核
                            echo Html::a('分管副总审核',\yii\helpers\Url::to(['/survey-list/deputy-manager-audit','id'=>$list['id']]),['class'=>'btn btn-xs bg-orange']);
                        }elseif($list['audit'] == 31 && ($list['office_money']+$list['company_money']) > 1000){
                            //若金额超过1000则总经理审核
                            echo Html::a('总经理审核',\yii\helpers\Url::to(['/survey-list/general-manager-audit','id'=>$list['id']]),['class'=>'btn btn-xs bg-orange']);
                        }
                    }?>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
            </table>
</div>
