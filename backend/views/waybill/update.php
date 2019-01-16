<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Waybill */

$this->title = '运单修改';
$this->params['breadcrumbs'][] = ['label' => '运单修改', 'url' => ['index']];
?>
<div class="waybill-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
