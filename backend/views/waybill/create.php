<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\assets\AppAsset;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model backend\models\Waybill */

//引入bill.js;
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl. '/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl. '/js/bill.js');
$this->title = '创建补全预录单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-create">
    <form>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <div class="box-body pad">
                    <table class="table text-right">
                        <tbody>
                            <tr>
                                <td width="10%">运单号<span class="text-red">*</span>：</td>
                                <td width="20%">
                                    <input type="text" name="codeNum" class="form-control"/>
                                </td>
                                <td width="10%">订单号<span class="text-red">*</span>：</td>
                                <td width="20%">
                                    <input type="text" name="orderNum" class="form-control"/>
                                </td>
                                <td>快慢线：</td>
                                <td class="text-left">
                                    <select name="speed" class="form-control">
                                        <option value="F">快线</option>
                                        <option value="S">慢线</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>超重发货?：</td>
                                <td class="text-center">
                                    <input type="radio" name="overWeightOut" value="1" checked/>发货
                                    <input type="radio" name="overWeightOut" value="0"/>不发货
                                </td>
                                <td>保价?：</td>
                                <td class="text-center">
                                    <input type="radio" name="valueInsured" value="1"/>保价
                                    <input type="radio" name="valueInsured" value="0" checked/>不保价
                                </td>
                                <td>保价金额：</td>
                                <td class="text-center"><input type="number" name="insuranceAmount" disabled/>元</td>
                            </tr>
                            <tr>
                                <td>目的国家<span class="text-red">*</span>：</td>
                                <td>
                                    <?php
                                        echo Select2::widget([
                                             'name' => 'countryId',
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
                                </td>
                                <td>收件人<span class="text-red">*</span>：</td>
                                <td>
                                    <input type="text" name="consigneeName" class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td>电话<span class="text-red">*</span>：</td>
                                <td>
                                    <input type="text" name="consigneeTel" class="form-control"/>
                                </td>
                                <td>邮编<span class="text-red">*</span>：</td>
                                <td>
                                    <input type="text" name="consigneeZip" class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td>省州<span class="text-red">*</span>：</td>
                                <td>
                                    <input type="text" name="consigneeState" class="form-control"/>
                                </td>
                                <td>城市<span class="text-red">*</span>：</td>
                                <td>
                                    <input type="text" name="consigneeCity" class="form-control"/>
                                </td>
                                <td>区县：</td>
                                <td>
                                    <input type="text" name="consigneeCounty" class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td>街道地址<span class="text-red">*</span>：</td>
                                <td>
                                    <input type="text" name="consigneeAddress1" class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td>客户名称<span class="text-red">*</span>：</td>
                                <td>
                                    <?php
                                    echo Select2::widget([
                                        'name' => 'memberName',
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
                                <td>仓库<span class="text-red">*</span>：</td>
                                <td>
                                    <select name="storageId" class="form-control">
                                        <?php
                                        foreach ($storage as $k=>$v){
                                            echo '<option value="'. $k .'">'. $v .'</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr/>
                    <table class="table text-center">
                        <thead>添加物品</thead>
                        <tbody id="goods-row">
                            <tr id="goods">
                                <td>
                                    名称(中)<span class="text-red">*</span>：<input name="nameCn" type="text" class="form-control input-group-sm"/>
                                </td>
                                <td>
                                    别名(英)<span class="text-red">*</span>：<input name="nameEn" type="text" class="form-control input-group-sm"/>
                                </td>
                                <td>
                                    代码<span class="text-red">*</span>：<input name="hsCode" type="text" class="form-control input-group-sm"/>
                                </td>
                                <td>
                                    单价<span class="text-red">*</span>：<input name="price" type="number" class="form-control input-group-sm"/>
                                </td>
                                <td>
                                    数量：<input name="quantity" type="number" class="form-control input-group-sm"/>
                                </td>
                                <td>
                                    重量：<input name="weight" type="number" class="form-control input-group-sm"/>
                                </td>
                                <td><br/>
                                    <input type="button" class="btn btn-success goods-add" value="添加">
                                </td>
                            </tr>
                            <tr>
                                <td>申报价值<span class="text-red">*</span>：</td>
                                <td>
                                    <input type="number" name="declareValue" class="form-control"/>
                                </td>
                                <td>入库重量<span class="text-red">*</span>：</td>
                                <td>
                                    <input type="number" name="weightInput" class="form-control"/>
                                </td>
                            </tr>
                            <tr>
                                <td>父渠道<span class="text-red">*</span>：</td>
                                <td>
                                    <?php
                                    echo Select2::widget([
                                        'name' => 'channelParentId',
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
                                </td>
                                <td>子渠道<span class="text-red">*</span>：</td>
                                <td>
                                    <select name="channelChildId" class="form-control">

                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>转单号：</td>
                                <td>
                                    <input type="text" name="expressNum" class="form-control"/>
                                    <input type="hidden" name="waybillId"/>
                                </td>
                                <td>
                                    <input type="button" class="btn btn-warning" id="auto-api" value="出单生成转单号"/>
                                </td>
                            </tr>
                            <tr>
                                <td>备注：</td>
                                <td>
                                    <textarea name="remark" rows="6" class="form-control"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>特殊要求：</td>
                                <td>
                                    <textarea name="remarkSpecial" rows="6" class="form-control"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>客户备注：</td>
                                <td>
                                    <input name="remarkMember" class="form-control"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="button" class="btn btn-success finish" value="完成">
                    <!--<button class="btn btn-success">创建</button>-->
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.col -->
    </div>
    </form>

</div>
