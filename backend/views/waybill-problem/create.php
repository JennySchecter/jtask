<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WaybillProblem */

$this->title = '设置问题件';
$this->params['breadcrumbs'][] = ['label' => '问题件列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-problem-create">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'waybillId')->textInput(['value'=>$id,'readonly'=>true]) ?>

    <div class="form-group">
        <label>出库与否</label>
        <select name="out" class="form-control">
            <option value="1">出库前</option>
            <option value="2">出库后</option>
        </select>
    </div>
    <?= $form->field($model, 'remark')->dropDownList(\backend\models\ProblemType::dropDownList()) ?>

    <div class="form-group">
        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
