<?php

/* @var $this yii\web\View */

$this->title = '急速系统后台';
?>
<div class="site-index">
    <div class="callout callout-info">
        <h4><?php
            $greetings = \backend\helpers\AdminFun::Config('greetings');
            echo str_replace('@',$admin['nickName'],$greetings);
            ?></h4>
    </div>
    <?php
    if($admin['kf_group']==1){
    ?>
    <!--客户支持组显示-->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?=$billProblemCount?></h3>
                    <p>问题件未处理</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?=yii\helpers\Url::to(['/waybill-problem/index'])?>" class="small-box-footer">查看<i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?=$problemOvertime?></h3>
                    <p>未处理超时</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="<?=yii\helpers\Url::to(['/waybill-problem/index'])?>" class="small-box-footer">查看<i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?=$dealOvertime?></h3>
                    <p>处理中</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="<?=yii\helpers\Url::to(['/waybill-problem/index'])?>" class="small-box-footer">查看<i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?=$pickCount?></h3>
                    <p>待取件</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="<?=yii\helpers\Url::to(['/pickup/index'])?>" class="small-box-footer">查看<i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-maroon">
                <div class="inner">
                    <h3><?=$pickOvertime?></h3>
                    <p>待取件超时</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="<?=yii\helpers\Url::to(['/pickup/index'])?>" class="small-box-footer">查看<i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <?php }elseif($admin['kf_group']==2){?>
    <!--渠道对接组显示-->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?=$surveyCount?></h3>
                    <p>异常件调查未处理</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?=yii\helpers\Url::to(['/survey-list/index'])?>" class="small-box-footer">查看<i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?=$surveyDealCount?></h3>
                    <p>处理中</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="<?=yii\helpers\Url::to(['/survey-list/index'])?>" class="small-box-footer">查看<i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?=$surveyOvertime?></h3>
                    <p>超时</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="<?=yii\helpers\Url::to(['/survey-list/index'])?>" class="small-box-footer">查看<i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <?php }elseif($admin['kf_group']==3){?>
    <!--外地客户组显示-->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?=$sendCount?></h3>
                    <p>外地客户寄件未处理</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="<?=yii\helpers\Url::to(['/user-send/index'])?>" class="small-box-footer">查看<i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?=$sendProblem?></h3>
                    <p>问题件</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="<?=yii\helpers\Url::to(['/user-send/index'])?>" class="small-box-footer">查看<i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <?php }?>
</div>
