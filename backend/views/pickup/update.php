<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Pickup */

$this->title = '设置取件状态 ';
?>
<div class="pickup-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
