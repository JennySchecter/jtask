<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\UserSend */

$this->title = '修改: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '我要寄件', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '修改', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="user-send-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
