<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserSend */

$this->title = 'Create User Send';
$this->params['breadcrumbs'][] = ['label' => 'User Sends', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-send-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
