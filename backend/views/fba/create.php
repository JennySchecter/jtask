<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Fba */

$this->title = 'Create Fba';
$this->params['breadcrumbs'][] = ['label' => 'Fbas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fba-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
