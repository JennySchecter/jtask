<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Group */

$this->title = '更新: ' . $model->groupname;
$this->params['breadcrumbs'][] = ['label' => '客户分组', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->groupname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
