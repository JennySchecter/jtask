<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-view">


    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确认要删除该用户吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    try{
         echo   DetailView::widget([
                'model' => $model,
                'attributes' => [
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
                    'groupid',
                ],
            ]) ;
    }catch (\Exception $e){

    } ?>

</div>
