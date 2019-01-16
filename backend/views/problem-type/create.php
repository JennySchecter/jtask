<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ProblemType */

$this->title = '添加问题类型';
$this->params['breadcrumbs'][] = ['label' => '问题类型', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="problem-type-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
