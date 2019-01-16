<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ProblemLog */

$this->title = 'Create Problem Log';
$this->params['breadcrumbs'][] = ['label' => 'Problem Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="problem-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
