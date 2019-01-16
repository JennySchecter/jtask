<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\UserSend */

$this->title = '创建寄件信息';
$this->params['breadcrumbs'][] = ['label' => '我要寄件', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-send-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
