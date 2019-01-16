<?php

use backend\assets\AppAsset;

AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl . '/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl . '/js/user.js');
$this->title = ''
?>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">欢迎语设置</h3>
    </div>
    <!-- form start -->
    <form class="form-horizontal" action="<?=yii\helpers\Url::to(['/admin/setting'])?>" method="post">
        <div class="box-body">
            <div class="form-group">
                <label for="greetings" class="col-sm-1 control-label">欢迎语</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="greetings"><?php
                        echo $greetings? $greetings:'';
                        ?>
                    </textarea>
                    <p class="help-block"><span style="color: red">注：会员名用@代替，例如"欢迎张三"可设置成"欢迎@"</span></p>
                </div>

                <!--<label>
                    <p class="help-block">注：会员名用@代替，例如欢迎张三可设置欢迎@.</p>
                </label>-->

            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-default">保存</button>
            <?php
            if(Yii::$app->session->has('msg')){
                echo '<span style="color: #00a157">'. Yii::$app->session->getFlash('msg') . '</span>';
            }
            ?>
        </div>
    </form>
</div>