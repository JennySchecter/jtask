<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Admin */

$this->title = '修改密码';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                    'method' => 'post',
                    'action' => ['change-pwd'],
            ]); ?>
            <?= $form->field($model, 'username')->label('账号')->textInput(['readonly' => true]) ?>
            <?= $form->field($model, 'password')->label('新密码')->passwordInput() ?>
            <?= $form->field($model, 'passwordC')->label('确认密码')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton('修改', ['class' => 'btn btn-primary']) ?>
                <?php
                if(Yii::$app->session->has('msg')){
                    echo '<span style="color: #00ca6d">'. Yii::$app->session->getFlash('msg') .'</span>';
                }
                ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
