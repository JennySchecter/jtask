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
$this->title = '运单追踪';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-index">
    <div class="box box-info">
        <div class="box-header">
        </div>
        <div class="box-body">
            <table class="table table-bordered table-hover dataTable" >
                <tr>
                    <td width="50%"><input type="text" name="expressNum" class="form-control" placeholder="请填写转单号"/></td>
                    <td><input type="button" value="追踪" class="btn bg-purple" id="single-trace"/></td>
                </tr>
            </table>
        </div>
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
                                <th>转单号</th>
                                <th>运单号</th>
                                <th>订单号</th>
                                <th>EmsKind</th>
                                <th>目的地</th>
                                <th>transKind</th>
                                <th>收件人</th>
                                <th>总数</th>
                                <th>总重</th>
                                <th>状态</th>
                                <th>交货日期</th>
                                <th>最新信息</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody id="trace-message">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>


