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
    <div class="box" style="overflow-x: auto;">
        <div class="box-header">
            <h3 class="box-title">运单追踪列表</h3>
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
                                <th>
                                    <input type="checkbox" name="expressNum-all"/>转单号
                                </th>
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
                            <tbody id="list">
                            <?php foreach ($traceLists as $k=>$v):?>
                            <?php if(is_array($v)){?>
                                <tr>
                                    <td><input type="checkbox" name="expressNum" value="<?=$k?>"><?=$k?></td>
                                    <td><?=$v['Response_Info']['Number']?></td>
                                    <td><?=$v['Response_Info']['referNbr']?></td>
                                    <td><?=$v['Response_Info']['EmsKind']?></td>
                                    <td><?=$v['Response_Info']['Destination']?></td>
                                    <td><?=$v['Response_Info']['transKind']?></td>
                                    <td><?=$v['Response_Info']['Receiver']?></td>
                                    <td><?=$v['Response_Info']['totalPieces']?></td>
                                    <td><?=$v['Response_Info']['totalWeigt']?></td>
                                    <td>
                                        <?php
                                        $s = $v['Response_Info']['status'];
                                        if($s == '其它异常' || $s == '扣关' || $s == '超时' || $s == '地址错误' || $s == '销毁'){
                                            echo '<span class="label label-danger">' . $s . '</span>';
                                        }else if($s == '转运中'){
                                            echo '<span class="label label-primary">' . $s . '</span>';
                                        }else if($s == '送达'){
                                            echo '<span class="label label-success">' . $s . '</span>';
                                        }else if($s == '未发送'){
                                            echo '<span class="label bg-gray">' . $s . '</span>';
                                        }else if($s == '已发送'){
                                            echo '<span class="label bg-black">' . $s . '</span>';
                                        }else if($s == '丢失' || $s == '退件'){
                                            echo '<span class="label bg-purple">' . $s . '</span>';
                                        }else{
                                            echo $s;
                                        }
                                        ?>
                                    </td>
                                    <td><?=$v['Response_Info']['deliveryDate']?></td>
                                    <td>
                                    <?=$v['trackingEventList'][count($v['trackingEventList'])-1]['details'];?>
                                    </td>
                                    <td>
                                        <?=Html::a('<span class="glyphicon glyphicon-info-sign"></span>查看',yii\helpers\Url::to(['/trace/detail','expressNum'=>$k,'batch'=>1]),['class'=>'btn btn-xs btn-primary'])?>
                                        <?php
                                        if($s == '其它异常' || $s == '扣关' || $s == '超时' || $s == '地址错误' || $s == '销毁' || $s == '丢失' || $s == '退件'){
                                            echo Html::a('<span class="glyphicon glyphicon-envelope">发送</span>',['/user-alarms/abnormal-mail','expressNum'=>$k],['class'=> 'btn btn-xs btn-success']);
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-block btn-info btn-xs more">加载更多</button><br/>
                        <?php
                        if($more){
                            echo '';
                        }
                        ?>
                        <form action="<?=yii\helpers\Url::to(['/trace/export'])?>" method="post">
                            <input type="hidden" name="exp-ids" value="">
                            <button><i class="fa fa-share-square-o"></i>选中导出</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>
<?php
$request_url = \yii\helpers\Url::to(['trace/ajax-pull']);
$js = <<<JS
    $(document).on('click','.more',function() {
        $(this).text('正在加载...');
        $(this).attr('disabled',true);
      $.get('{$request_url}',{},function(data) {
          $('#list').append(data.html);
          if(data.more == 0){
              $('.more').text('没有更多了！');
              $('.more').attr('disabled',true);
          }else{
              $('.more').text('加载更多');
              $('.more').attr('disabled',false);
          }
      },'json');
    })
JS;
$this->registerJs($js);
?>

