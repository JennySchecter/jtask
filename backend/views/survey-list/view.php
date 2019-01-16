<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model backend\models\SurveyList */
//引入user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/user.js');
$this->title = '调查工单';
$this->params['breadcrumbs'][] = ['label' => '查看调查理赔流程工单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-list-create">

    <div class="box">
        <div class="box-header center-block">
            <h3 align="center">调查工单</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-condensed table-bordered text-center">
                <tbody>
                <tr>
                    <td>调查类型：</td>
                    <td>
                        <div class="form-control">
                            <?php
                            $investigate = \backend\models\InvestigateType::find()->where(['id'=>$model->it_id])->one();
                            echo $investigate['dc_name'];
                            ?>
                        </div>
                    </td>
                    <td>调查编号：</td>
                    <td>
                        <div class="form-control">
                            <?=$model->dc_num?>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>订单编号：</td>
                    <td>
                        <div class="form-control"><?=$model->order_num?></div>
                    </td>
                    <td>客户名称：</td>
                    <td><div class="form-control"><?=$model->member_name?></div></td>
                    <td>调查渠道：</td>
                    <td><div class="form-control"><?=$model->dc_channel?></div></td>
                </tr>
                <tr aria-colspan="3">
                    <td>情况描述：</td>
                    <td colspan="5">
                        <div>
                            <?php
                            $ds_arr = explode(';',$model->description);
                            $str = '';
                            $i = 1;
                            foreach ($ds_arr as $k=>$v){
                                $str .=  $i.'.'.$v.'<br/>';
                                $i++;
                            }
                            echo $str;
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>创建人：</td>
                    <td colspan="2"><?=$model->create_user?></td>
                    <td>创建日期：</td>
                    <td colspan="2"><?=date('Y-m-d H:i:s',$model->c_time)?></td>
                </tr>
                <tr aria-colspan="3">
                    <td>反馈描述：</td>
                    <td colspan="5">
                        <div>
                            <?php
                            if($model->deal_num > 0){
                                $fb_arr = explode(';',$model->feedback);
                                $str = '';
                                $i = 1;
                                foreach ($fb_arr as $k=>$v){
                                    $str .=  $i.'.'.$v.'<br/>';
                                    $i++;
                                }
                                echo $str;
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>处理人：</td>
                    <td colspan="2"><?=$model->deal_user?></td>
                    <td>处理日期：</td>
                    <td colspan="2"><?=date('Y-m-d H:i:s',$model->deal_time)?></td>
                </tr>
                <tr aria-rowspan="3">
                    <td>归档意见：</td>
                    <td colspan="5">
                        <div class="form-control">
                            <?=$model->file_content?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>理赔金额：</td>
                    <td colspan="2">
                        <?php
                        if($model->office_money){
                            echo '官方：'.$model->office_money.'元'.'&nbsp&nbsp';
                        }
                        if($model->company_money){
                            echo '公司：'.$model->company_money.'元';
                        }
                        ?>
                    </td>
                    <td>承担人：</td>
                    <td colspan="2">
                        <?php
                            if($model->staffIds){
                                $ids = explode(';',$model->staffIds);
                                $staffs = \backend\models\Admin::find()->where(['in','id',$ids])->asArray()->all();
                                foreach ($staffs as $staff){
                                    echo $staff['nickName'].'；';
                                }
                            }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="row no-print">
        <div class="col-xs-12">
            <button class="btn btn-default print"><i class="fa fa-print"></i> 打印</button>
        </div>
    </div>
</div>
