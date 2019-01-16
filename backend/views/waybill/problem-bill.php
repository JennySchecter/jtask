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
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/user.js');
$this->title = '问题件运单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-index">
    <div class="box box-info">
        <!-- form start -->
        <form class="form-horizontal">
            <?php
            $form = ActiveForm::begin([
                'action' => ['problem-bill'],
                'method' => 'get',
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
                        <!--<input type="text" class="form-control" placeholder="客户名">-->
                    </div>
                    <label for="" class="col-xs-1 control-label">取件人：</label>
                    <div class="col-xs-2">
                        <?php
                        echo $form->field($searchModel,'pickUserName')->label(false)->widget(Select2::className(),[
                            'options' => ['placeholder' => '请选择...'],
                            'pluginOptions' => [
                                'placeholder' => 'search ...',
                                'allowClear' => true,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                                ],
                                'ajax' => [
                                    'url' => yii\helpers\Url::to(['/admin/search-admin']),
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
                    <label for="" class="col-xs-1 control-label">财务：</label>
                    <div class="col-xs-2">
                        <?php
                        echo $form->field($searchModel,'financeId')->label(false)->widget(Select2::className(),[
                            'options' => ['placeholder' => '请选择...'],
                            'pluginOptions' => [
                                'placeholder' => 'search ...',
                                'allowClear' => true,
                                'language' => [
                                    'errorLoading' => new JsExpression("function () { return 'Waiting...'; }"),
                                ],
                                'ajax' => [
                                    'url' => yii\helpers\Url::to(['/admin/search-admin']),
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                ],
                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                'templateResult' => new JsExpression('function(res) { return res.text; }'),
                                'templateSelection' => new JsExpression('function (res) { return res.text; }'),
                            ],
                        ]);
                        ?>
                        <!--<input type="password" class="form-control" placeholder="财务">-->
                    </div>
                    <label for="" class="col-xs-1 control-label"></label>
                    <div>
                        <a href="javascript:;" onclick="stretch(this)" key="close"><span class="glyphicon glyphicon-chevron-down">展开</span></a>
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label for="" class="col-xs-1 control-label">订单号：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'orderNum')->label(false)->textInput(['placeholder'=>'订单号'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">转单号：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'expressNum')->label(false)->textInput(['placeholder'=>'转单号'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">运单号：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'codeNum')->label(false)->textInput(['placeholder'=>'运单号'])?>
                    </div>
                </div>
                <div class="form-group" style="display: none">
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
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label for="" class="col-xs-1 control-label">收件人：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'consigneeName')->label(false)->textInput(['placeholder'=>'收件人'])?>
                    </div>
                    <label for="" class="col-xs-2 control-label">收件号码：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'consigneeTel')->label(false)->textInput(['placeholder'=>'收件号码'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">邮编：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'consigneeZip')->label(false)->textInput(['placeholder'=>'邮编','class'=>'form-control'])?>
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label for="" class="col-xs-1 control-label">省州：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'consigneeState')->label(false)->textInput(['placeholder'=>'省州'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">城市：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'consigneeCity')->label(false)->textInput(['placeholder'=>'城市'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">区县：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'consigneeCounty')->label(false)->textInput(['placeholder'=>'区县'])?>
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label for="" class="col-xs-1 control-label">入库重量：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'weightInputStart')->label(false)->textInput(['placeholder'=>'入库起始重量'])?>
                        <?=$form->field($searchModel,'weightInputEnd')->label(false)->textInput(['placeholder'=>'入库截止重量'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">出库重量：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'weightOutputStart')->label(false)->textInput(['placeholder'=>'出库起始重量'])?>
                        <?=$form->field($searchModel,'weightOutputEnd')->label(false)->textInput(['placeholder'=>'出库截止重量'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">创建时间：</label>
                    <div class="col-xs-3">
                        <?php
                        echo $form->field($searchModel,'timeCreateStart')->label(false)->widget(DateTimePicker::className(),[
                            'options' => ['placeholder' => '请选择创建时间起'],
                            'language' => 'zh-CN',
                            'pluginOptions' => [
                                'autoclose' => true,
                            ]
                        ])
                        ?>
                        <?php
                        echo $form->field($searchModel,'timeCreateEnd')->label(false)->widget(DateTimePicker::className(),[
                            'options' => ['placeholder' => '请选择创建时间止'],
                            'language' => 'zh-CN',
                            'pluginOptions' => [
                                'autoclose' => true,
                            ]
                        ])
                        ?>
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label for="" class="col-xs-1 control-label">入库时间：</label>
                    <div class="col-xs-3">
                        <?php
                        echo $form->field($searchModel,'time_in_start')->label(false)->widget(DateTimePicker::className(),[
                            'options' => ['placeholder' => '请选择入库时间起'],
                            'language' => 'zh-CN',
                            'pluginOptions' => [
                                'autoClose' => true,
                            ]
                        ])
                        ?>
                        <?php
                        echo $form->field($searchModel,'time_in_end')->label(false)->widget(DateTimePicker::className(),[
                            'options' => ['placeholder' => '请选择入库时间止'],
                            'language' => 'zh-CN',
                            'pluginOptions' => [
                                'autoClose' => true,
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
                                'autoClose' => true,
                            ]
                        ])
                        ?>
                        <?php
                        echo $form->field($searchModel,'time_out_end')->label(false)->widget(DateTimePicker::className(),[
                            'options' => ['placeholder' => '请选择出库时间止'],
                            'language' => 'zh-CN',
                            'pluginOptions' => [
                                'autoClose' => true,
                            ]
                        ])
                        ?>
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label for="" class="col-xs-1 control-label">状态：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'status')->label(false)->dropDownList(Yii::$app->params['waybillStatus'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">航班号：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'flyNo')->label(false)->textInput(['placeholder'=> '航班号'])?>
                    </div>
                    <label for="" class="col-xs-1 control-label">包号：</label>
                    <div class="col-xs-2">
                        <?=$form->field($searchModel,'bagNo')->label(false)->textInput(['placeholder' => '包号'])?>
                    </div>
                </div>
                <div class="form-group" style="display: none">
                    <label class="col-xs-1 control-label">附加：</label>
                    <div class="col-xs-2">
                        <?php $searchModel->recycle = 0;?>
                        <?=$form->field($searchModel,'recycle')->label(false)->radioList([0=>'不包含回收站',1=>'包含回收站'])?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-info">查找</button>
                <?=Html::a('导出',yii\helpers\Url::to(['/waybill/problem-export']),['class'=>'btn btn-default'])?>
            </div>
            <?php ActiveForm::end();?>
            <!-- /.box-footer -->
        </form>
    </div>
    <div class="box" style="overflow-x: auto;">
        <div class="box-header">
            <h3 class="box-title">运单列表</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                            <tr role="row">
                                <th>运单号</th>
                                <th>订单号</th>
                                <th>转单号</th>
                                <th>客户</th>
                                <th>渠道</th>
                                <th style="width: 5%">目的国家</th>
                                <th>创建时间</th>
                                <th>入库时间</th>
                                <th>出库时间</th>
                                <th>入库重量</th>
                                <th>出库重量</th>
                                <th>备注</th>
                                <th>航班号</th>
                                <th style="width: 6%">异常状态</th>
                                <th style="width: 10%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; foreach ($waybills as $waybill):?>
                                <tr role="row" class="<?= $i%2==1? 'odd':'even' ;?>">
                                    <td><?php
                                        $userModel = \backend\models\User::find()->where(['username'=>$waybill['memberName']])->one();
                                        echo $userModel['isvip']==1 ? $waybill['codeNum'] . '<span class="badge bg-red">vip运单</span>': $waybill['codeNum'];
                                        ?></td>
                                    <td><?=$waybill['orderNum']?></td>
                                    <td><?=$waybill['expressNum']?></td>
                                    <td><?=$waybill['memberName']?></td>
                                    <td><?php
                                        $channelParent = \backend\models\Channel::find()->where(['id'=>$waybill['channelParentId']])->one();
                                        $channelChild = \backend\models\Channel::find()->where(['id'=>$waybill['channelChildId']])->one();
                                        echo $channelParent['name'] . '-' . $channelChild['name'];
                                        ?></td>
                                    <td><?php
                                        $country = \backend\models\Country::find()->where(['id'=>$waybill['countryId']])->one();
                                        echo $country['name'];
                                        ?></td>
                                    <td><?=$waybill['waybillActioner']['timeCreate']? date('Y-m-d H:i:s',$waybill['waybillActioner']['timeCreate']) : ''?></td>
                                    <td><?=$waybill['waybillActioner']['timeIn']? date('Y-m-d H:i:s',$waybill['waybillActioner']['timeIn']) : ''?></td>
                                    <td><?=$waybill['waybillActioner']['timeOut']? date('Y-m-d H:i:s',$waybill['waybillActioner']['timeOut']) : ''?></td>
                                    <td><?=$waybill['weightInput']?></td>
                                    <td><?=$waybill['weightOutput']?></td>
                                    <td><?=$waybill['remark']?></td>
                                    <td><?=$waybill['flyNo']?></td>
                                    <td><?php
                                        echo
                                        Yii::$app->params['abnormalDomesticStatus'][$waybill['waybillStatus']['statusAbnormalDomestic']];
                                        ?></td>
                                    <td>
                                        <?=Html::a('<i class="fa fa-fw fa-info"></i>查看',yii\helpers\Url::to(['/waybill/view','id'=>$waybill['id'],'timeIn'=>$waybill['timeIn']]),['class' => 'btn btn-default btn-xs'])?>
                                        <?=Html::a('<i class="fa fa-edit"></i>修改',yii\helpers\Url::to(['/waybill/update','id'=>$waybill['id']]),['class'=>'btn bg-purple btn-xs'])?>
                                        <?=Html::button('<i class="fa fa-fw fa-trash-o"></i>删除',[
                                            'class' => 'btn btn-xs btn-danger delete',
                                            'key' => $waybill['id']
                                        ])?>
                                        <?=Html::a('<span class="glyphicon glyphicon-tags">发送</span>',yii\helpers\Url::to(['/user-alarms/problem-mail','id'=>$waybill['id']]),['class'=> 'btn btn-xs bg-aqua'])?>
                                        <?=Html::a('<button class="btn btn-xs btn-success"><i  class="fa fa-fw fa-warning"></i>异常件调查</button>',yii\helpers\Url::to(['/survey-list/auto-create','id'=>$waybill['id']]))?>
                                        <?=Html::a('<button class="btn btn-xs btn-adn">转为正常件</button>',yii\helpers\Url::to(['/waybill/setnormal','id'=>$waybill['id']]));?>
                                    </td>
                                </tr>
                                <?php $i++;?>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">共<?=$total?>条数据</div>
                    </div>
                    <div class="col-sm-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            <!--<ul class="pagination pagination-sm no-margin pull-right">
                                <li><a href="#">«</a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">»</a></li>
                            </ul>-->
                            <?=LinkPager::widget([
                                'pagination' => $pager,
                                'options' => [
                                    'class' => 'pagination pagination-sm no-margin pull-right',
                                ],

                            ])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>


