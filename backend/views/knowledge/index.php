<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\grid\GridView;
use backend\assets\AppAsset;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;
use yii\web\JsExpression;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\WaybillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//加载user.js
AppAsset::register($this);
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/ext/layer/layer.js');
AppAsset::addScript($this,Yii::$app->request->baseUrl.'/js/user.js');
$this->title = '知识库列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-index">
    <div class="box">
        <div class="box-header">
            <p><?=Html::a('新增',yii\helpers\Url::to(['/knowledge/add']),['class' => 'btn btn-success'])?></p>
            <h3 class="box-title">知识库列表</h3>
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
                        <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                            <tr role="row">
                                <th>ID</th>
                                <th>主题</th>
                                <th>上传时间</th>
                                <th>上传者</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; foreach ($fileLists as $file):?>
                                <tr role="row" class="<?= $i%2==1? 'odd':'even' ;?>">
                                    <td><?=$file['id']?></td>
                                    <td><?=$file['subject']?></td>
                                    <td><?=date('Y-m-d H:i:s',$file['create_time'])?></td>
                                    <td><?=$file['admin_username']?></td>
                                    <td>
                                        <!--<a href="<?/*=$file['attachment_path']*/?>" download="<?/*=$_SERVER['HTTP_HOST'].$file['attachment_path']*/?>"><i class="fa fa-download"></i>下载</a>-->
                                        <?=Html::a('<i class="fa fa-fw fa-download"></i>下载',yii\helpers\Url::to(['/knowledge/download','filename'=>$file['attachment_path']]),['class' => 'btn btn-xs btn-warning'])?>
                                        <?=Html::button('<i class="fa fa-trash-o"></i>删除',[
                                            'class' => 'btn btn-xs btn-danger knowledge-del',
                                            'key' => $file['id']
                                        ])?>
                                    </td>
                                </tr>
                                <?php $i++;?>
                            <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">共<?=$total?>条数据</div>
                    </div>
                    <div class="col-sm-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            <!--<ul class="pagination pagination-sm no-margin pull-right">
                                <li><a href="#">«</a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">»</a></li>
                            </ul>-->
                            <?=LinkPager::widget([
                                'pagination' => $pager,
                                'options' => [
                                    'class' => 'pagination pagination-sm no-margin pull-right',
                                ],

                            ])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>
<?php
$requestDel = yii\helpers\Url::toRoute('del');
$js = <<<JS
    $(document).on('click','.knowledge-del',function() {
      var id = $(this).attr('key');
           layer.confirm('您确定删除该文档？',{
               btn:['确定','取消']
           },function() {
               $.post('{$requestDel}',{'id':id},function(data) {
                 layer.msg(data.errorMsg);
               if(data.errorCode == 0){
                   setTimeout(function() {
                     location.reload();
                   },2000);
               }
             },'json');
           })
    });
JS;

$this->registerJs($js);
?>
