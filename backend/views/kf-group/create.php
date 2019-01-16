<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\KfGroup */

$this->title = '创建客服分组';
$this->params['breadcrumbs'][] = ['label' => 'Kf Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kf-group-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
