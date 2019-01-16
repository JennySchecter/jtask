<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Pickup */

$this->title = '我要取件';
$this->params['breadcrumbs'][] = ['label' => '创建取件申请'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pickup-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
