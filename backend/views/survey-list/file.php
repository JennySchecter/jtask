<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '提交归档';
?>

<div class="box">
    <div class="box-body no-padding">
        <div class="box-body">
            <form role="form" action="<?=yii\helpers\Url::to(['/survey-list/file'])?>" method="post">
                <div class="form-group">
                    <label>归档意见:</label>
                    <input class="form-control" name="file_content"/>
                    <input type="hidden" name="id" value="<?=$id?>">
                </div>
                <div class="form-group">
                    <input type="submit" value="提交" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>
