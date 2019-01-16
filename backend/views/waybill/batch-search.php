<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use backend\assets\AppAsset;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\WaybillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//加载user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/bill.js');
$this->title = '批量查询运单列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-index">
    <div class="box box-info">
        <?php
        $form = ActiveForm::begin([
            'action' => ['batch-search'],
            'method' => 'post',
            'options' => ['enctype' => 'multipart/form-data'],
        ]);
        ?>
        <div class="box-body">
            <div class="form-group">
                <?=$form->field($model,'txt')->fileInput()?>
                <p class="help-block">txt文档中，每一行是一个单号</p>
                <input type="radio" name="numType" value="orderNum" checked/>订单号文本查询
                <input type="radio" name="numType" value="codeNum"/>运单号文本查询
                <input type="radio" name="numType" value="expressNum"/>转单号文本查询

            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?=Html::submitButton('查找',['class'=>'btn btn-info'])?>
            <?=Html::a('导出',yii\helpers\Url::to(['waybill/batch-export']),['class'=>'btn btn-default'])?>
        </div>
        <?php ActiveForm::end();?>
        <!-- /.box-footer -->
    </div>
    <div class="box">
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
                                <th>入库时间</th>
                                <th>出库时间</th>
                                <th>出库重量</th>
                                <th>特殊要求</th>
                                <th>航班号</th>
                                <th style="width: 6%">状态</th>
                                <th style="width: 10%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if($waybills){
                                ?>
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
                                                <td><?=$waybill['waybillActioner']['timeIn']? date('Y-m-d H:i:s',$waybill['waybillActioner']['timeIn']) : ''?></td>
                                                <td><?=$waybill['waybillActioner']['timeOut']? date('Y-m-d H:i:s',$waybill['waybillActioner']['timeOut']) : ''?></td>
                                                <td><?=$waybill['weightOutput']?></td>
                                                <td><?=$waybill['remarkSpecial']?></td>
                                                <td><?=$waybill['flyNo']?></td>
                                                <td><?php
                                                    echo
                                                    \backend\helpers\AdminFun::getStatusValue($waybill['waybillStatus']['status']);
                                                    ?></td>
                                                <td>
                                                    <?=Html::a('<i class="fa fa-fw fa-info"></i>查看',yii\helpers\Url::to(['/waybill/view','id'=>$waybill['id'],'timeIn'=>$waybill['timeIn']]),['class' => 'btn btn-default btn-xs'])?>
                                                    <?=Html::a('<i class="fa fa-edit"></i>修改',yii\helpers\Url::to(['/waybill/update','id'=>$waybill['id']]),['class'=>'btn bg-purple btn-xs'])?>
                                                    <?=Html::button('<i class="fa fa-fw fa-trash-o"></i>删除',[
                                                        'class' => 'btn btn-xs btn-danger delete',
                                                        'key' => $waybill['id']
                                                    ])?>
                                                    <?=Html::a('<button class="btn btn-xs btn-success"><i  class="fa fa-fw fa-warning"></i>异常件调查</button>',yii\helpers\Url::to(['/survey-list/auto-create','id'=>$waybill['id']]))?>
                                                    <?php
                                                    //异常件(问题件)则可转为正常件
                                                    if($waybill['waybillStatus']['statusAbnormalDomestic'] ==2 || $waybill['waybillStatus']['statusAbnormalForeign'] ==2){
                                                        echo Html::a('<button class="btn btn-xs btn-adn">转为正常件</button>',yii\helpers\Url::to(['/waybill/setnormal','id'=>$waybill['id'],'timeIn' => $waybill['timeIn']]));
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php $i++;?>
                                        <?php endforeach;?>
                                <?php
                                    }
                                ?>
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
                            <?php
                                if($pager!=''){
                                    echo LinkPager::widget([
                                        'pagination' => $pager,
                                        'options' => [
                                            'class' => 'pagination pagination-sm no-margin pull-right',
                                        ],
                                    ]);
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>


