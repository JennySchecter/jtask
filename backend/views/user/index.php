<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\AppAsset;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//引入userjs
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/user.js');

$this->title = '客户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>



    <div class="form-group">
        <?php $form = ActiveForm::begin([
            'action' => ['export'],
            'method' => 'get',
        ]); ?>
        <?= Html::submitButton('导出数据', ['class' => 'btn btn-primary']) ?>

    </div>
    <?php
    try{
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'format'=>'raw',
                    'label'=>'全/反选',
                    'attribute'=>'',
                    'header'=>"<input type='checkbox' id='all'/>",
                    'value' => function ($model) {
                        return "<input type='checkbox' class='i-checks' value={$model['id']} name='ids'>";
                    },
                ],
                'id',
                [
                    'attribute' => 'username',
                    'format' => 'html',
                    'value' => function($model){
                        return $model->isvip==1? $model->username . '  <span class="badge bg-red">vip</span>' : $model->username;
                    }
                ],
                'name',
                [
                    'attribute'=>'groupid',
                    'format' =>'raw',
                    'value'=>function($model){
                        if(!empty($model->groupid)){
                            $group = \backend\models\Group::find()->where(['id'=>$model->groupid])->one();
                            return $group['groupname'];
                        }else{
                            return '';
                        }
                    },
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function($model){
                        return $model->status==1 ? '正常':'禁用';
                    },
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'isvip',
                    'value' => function($model){
                        return $model->isvip==1 ? '是(<a href="javascript:;" class="label label-danger" onclick="cancelvip('.$model->id.')">取消vip</a>)':'否(<a href="javascript:;" class="label label-success" onclick="setvip('.$model->id.')">点击设为vip</a>)';
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{view}',
                    'buttons' => [
                            'view' => function($url){
                                return Html::a('<button class="btn btn-sm btn-primary">查看</button>',$url);
                            }
                    ],
                ],
            ],
        ]);
    }catch(\Exception $e){

    }
     ?>
    <?php ActiveForm::end(); ?>
    <div class="form-group">
        <label for="name">批量分配分组</label>
        <select class="form-control"  name="group">
            <?php foreach ($groups as $group):?>
                <option value="<?=$group['id']?>"><?=$group['groupname']?></option>
            <?php endforeach;?>
        </select>
        <br/>
        <button class="btn btn-success" id="cid-user">分配至该组</button>
    </div>
</div>



