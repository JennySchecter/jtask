<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\web\JsExpression;
$this->title = '异常件通知';
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"></h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <?php $form = ActiveForm::begin([
        'action' => ['abnormal-mail'],
        'method' => 'post'
    ])?>
        <div class="box-body">
            <div class="form-group">
                <?=$form->field($model,'userId')->hiddenInput(['value'=>$waybill['memberId']])?>
                <input type="text" value="<?=$waybill['memberName']?>" readonly class="form-control"/>
                <input type="hidden" value="<?=$waybill['id']?>" name="id" class="form-control"/>
            </div>
            <div class="form-group">
                <label class="control-label">转单号</label>
                <input type="text" value="<?=$waybill['expressNum']?>"  class="form-control" readonly/>
                <span style="color: red">该异常件已提醒过<?=$times?>次</span>
            </div>
            <div class="form-group">
                <?=$form->field($model,'subject')->textInput()?>
            </div>
            <div class="form-group">
                <?=$form->field($model,'content')->textarea()?>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?=Html::submitButton('发送',['class' => 'btn btn-primary'])?>
            <?php
            if(Yii::$app->session->has('msg')){
                echo '<span style="color: #00a157">'.Yii::$app->session->getFlash('msg').'</span>';
            }
            ?>
        </div>
    <?php ActiveForm::end();?>
    <?php
    if(!empty($userAlarms)) {
        ?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">该异常件通知记录</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example2" class="table table-bordered table-hover dataTable" role="grid"
                                   aria-describedby="example2_info">
                                <thead>
                                <tr role="row">
                                    <th>转单号</th>
                                    <th>客户ID</th>
                                    <th>发送主题</th>
                                    <th>内容</th>
                                    <th>日期</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0;
                                foreach ($userAlarms as $userAlarm): ?>
                                    <tr role="row" class="<?= $i % 2 == 1 ? 'odd' : 'even'; ?>">
                                        <td><?=$userAlarm['expressNum']?></td>
                                        <td><?=$userAlarm['userId']?></td>
                                        <td><?=$userAlarm['subject']?></td>
                                        <td><?=$userAlarm['content']?></td>
                                        <td><?=date('Y-m-d H:i:s',$userAlarm['datetime'])?></td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <?php
    }
    ?>
</div>
