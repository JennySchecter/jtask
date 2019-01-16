<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\InvestigateType */

$this->title = '新增调查类型';
$this->params['breadcrumbs'][] = ['label' => '调查类型', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="investigate-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
