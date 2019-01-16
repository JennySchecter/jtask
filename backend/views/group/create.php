<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Group */

$this->title = '添加分组';
$this->params['breadcrumbs'][] = ['label' => '分组列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
