<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProblemTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '问题类型';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="problem-type-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加问题类型', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    try{
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                'id',
                'probdesc',
                'c_time:datetime',
                'u_time:datetime',
                'createby',

                [
                     'class' => 'yii\grid\ActionColumn',
                    'header'=>'操作',
                    'template' => '{update}{delete}',
                    'buttons' => [
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
    }catch(\Exception $e){

    }
    ?>
</div>
