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
$this->title = '审核进度';
$this->params['breadcrumbs'][] = ['label' => '调查工单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-list-create">
    <ul class="timeline">
        <!-- timeline item -->
        <?php foreach ($auditLists as $list):?>
            <li>
                <?php
                echo $list['flag'] == 0 ? '<span class="glyphicon glyphicon-remove bg-red"></span>':'<span class="glyphicon glyphicon-ok bg-green"></span>';
                ?>
                <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> <?=date('Y-m-d H:i:s',$list['time'])?></span>
                    <h3 class="timeline-header no-border"><?=$list['desc']?>（来自：<?=$list['operator_name']?>）</h3>
                </div>
            </li>
        <?php endforeach;?>
        <!-- END timeline item -->
        <li>
            <i class="fa fa-clock-o bg-gray"></i>
        </li>
    </ul>
</div>
