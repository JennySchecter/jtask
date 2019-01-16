<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserSend */

$this->title = '设为问题件: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'User Sends', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '设为问题件';
?>
<div class="user-send-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
