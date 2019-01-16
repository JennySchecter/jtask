<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\AppAsset;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//引入user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/user.js');

$this->title = '后台用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加新用户', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php try{
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => '',
                    'format' => ['raw'],
                    'label' => "全/反选",
                    'headerOptions' => ['width' => '50','style'=>'cursor:pointer'],
                    'contentOptions' => ['align'=>'center'],
                    'header'=>"<input type='checkbox' id='all'/>",
                    'value' => function ($data) {
                        return "<input type='checkbox' class='i-checks' value={$data['id']} name='ids'>";
                    },
                ],
                'id',
                'username',
                'nickName',
                'datetime:datetime',
                [
                    'format' => 'raw',
                    'attribute' => 'status',
                    'value' => function($model){
                        return $model->status==1 ? '正常':'停用';
                    },
                ],
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
                    'attribute'=>'kf_group',
                    'format' =>'raw',
                    'value'=>function($model){
                        if(!empty($model->kf_group)){
                            $group = \backend\models\KfGroup::find()->where(['id'=>$model->kf_group])->one();
                            return $group['groupname'];
                        }else{
                            return '';
                        }
                    },
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'template' => '{view}{update}{delete}',
                    'buttons' => [
                        'view' => function($url){
                            return Html::a('<button class="btn btn-sm btn-primary">查看</button>',$url);
                        },
                        'update' => function($url){
                            return Html::a('<button class="btn btn-sm btn-success">编辑</button>',$url);
                        },
                        'delete' => function($url){
                            $options = [
                                'title' => '删除',
                                'aria-label' => '删除',
                                'data-confirm' => '确认删除?',
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<button class="btn btn-sm btn-danger">删除</button>',$url,$options);
                        },
                    ],
                ],
            ],
             'options' => ['class'=>'table table-striped table-bordered table-hover'],
        ]);
    }catch (\Exception $e){

    }
    ?>

    <div class="form-group">
        <label for="name">批量分配分组</label>
        <select class="form-control"  name="group">
            <?php foreach ($groups as $group):?>
                <option value="<?=$group['id']?>"><?=$group['groupname']?></option>
            <?php endforeach;?>
        </select>
        <br/>
        <button class="btn btn-success" id="cid">分配至该组</button>
    </div>
</div>

