<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 2018/11/7
 * Time: 16:05
 */
$this->title = '批量导入客户';
?>
<div class="box box-primary">

            <!-- /.box-header -->
            <!-- form start -->
            <?php $form =ActiveForm::begin([
                'action' => ['import'],
                'method' => 'post',
                'options' => ['enctype' => 'multipart/form-data']
            ]);?>
              <div class="box-body">
                <div class="form-group">
                  <?=$form->field($model,'file')->fileInput()?>

                  <p class="help-block">请上传文件,请保证字段与用户表一致</p>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <?=Html::submitButton('导入数据',['class'=>'btn btn-primary'])?>
                <?php
                    if(Yii::$app->session->has('msg')){
                        echo '<span style="color: #00a65a">'.Yii::$app->session->getFlash('msg').'</span>';
                    }
                ?>
              </div>
            <?php ActiveForm::end();?>
          </div>