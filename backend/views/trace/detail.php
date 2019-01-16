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
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">轨迹信息</h3>
            <?php
            if($batch == 1){
                echo  '<br/>'.Html::a('<i class="fa fa-fw fa-mail-reply"></i>返回',yii\helpers\Url::to(['trace/batch-trace']),['class'=>'btn btn-success']);
            }
            ?>
            <?php
            $s = $status;
            if($s == '其它异常' || $s == '扣关' || $s == '超时' || $s == '地址错误' || $s == '销毁' || $s == '丢失' || $s == '退件'){
                $abnormal = \backend\models\AbnormalNotify::find()->where(['express_num'=>$expressNum])->one();
                if($abnormal['count']){
                    echo '<br/><span style="color: red;">该异常件已通知'. $abnormal['count'] .'次</span>';
                }
            }
            ?>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                            <tr role="row">
                                <th>时间</th>
                                <th>地点</th>
                                <th>轨迹信息</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($traces as $v):?>
                                <tr>
                                    <td><?=$v['date']?></td>
                                    <td><?=$v['place']?></td>
                                    <td><?=$v['details']?></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>


