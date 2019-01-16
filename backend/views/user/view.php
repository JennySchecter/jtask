<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '用户列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <?php
    try{
        echo    DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'username',
                    'email:email',
                    'role',
                    'created_at',
                    'updated_at',
                    'name',
                    'code',
                    'mobile',
                    'wechat',
                    'qq',
                    'address',
                    'address2',
                    'address3',
                    'address4',
                    'address5',
                    'paperworkCode',
                    'paperworkCode2',
                    'balance',
                    'balance1',
                    'balance2',
                    'balance3',
                    'creditMoney',
                    'goodsName',
                    'goodsEnglish',
                    'goodsCode',
                    'goodsPrice',
                    'special',
                    'contract',
                    'payType',
                    'authSimple',
                    'authReturnApiError',
                    'apiKey',
                    'esyUser',
                    'esyPass',
                    'token',
                    'storageId',
                    'department',
                    'status',
                    'groupid',
                    [
                        'format' => 'raw',
                        'attribute' => 'isvip',
                        'value' => function($model){
                            return $model->isvip==1 ? '是':'否';
                        },
                    ],
                ],
            ]) ;
    }catch(\Exception $e){

    }
    ?>

</div>
