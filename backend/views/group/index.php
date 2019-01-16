<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客户分组列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">

    <p>
        <?= Html::a('添加分组', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    try{
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                'id',
                'groupname',
                'createtime:datetime',
                'updatetime:datetime',
                'creatby',

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
        ]);
    }catch (\Exception $e){

    }
     ?>
</div>
