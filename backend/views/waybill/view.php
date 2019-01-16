<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Waybill */

$this->title = '运单详细信息';
$this->params['breadcrumbs'][] = ['label' => '运单列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waybill-view">

    <?php
    try{
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'codeNum',
                'orderNum',
                'expressNum',
                'declareNum',
                'memberId',
                'memberName',
                'memberCode',
                [
                    'attribute' => 'channelParentId',
                    'format' => 'raw',
                    'value' => function($model){
                        $channel = \backend\models\Channel::find()->where(['id'=>$model->channelParentId])->one();
                        return $channel['name'];
                    }
                ],
                [
                    'attribute' => 'channelChildId',
                    'format' => 'raw',
                    'value' => function($model){
                        $channel = \backend\models\Channel::find()->where(['id'=>$model->channelChildId])->one();
                        return $channel['name'];
                    }
                ],
                [
                    'attribute' => 'storageId',
                    'format' => 'raw',
                    'value' => function($model){
                        $storage = \backend\models\Storage::find()->where(['id'=>$model->storageId])->one();
                        return $storage['name'];
                    }
                ],
                [
                    'attribute' => 'countryId',
                    'format' => 'raw',
                    'value' => function($model){
                        $country = \backend\models\Country::find()->where(['id'=>$model->countryId])->one();
                        return $country['name'];
                    }
                ],
                'consigneeId',
                //'financeId',
                //'statusId',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'label' => '状态',
                    'value' => function($model){
                            return \backend\models\Waybill::getStatus($model->id);
                    }
                ],
                'weightInput',
                'timeIn:datetime',
                'weightOutput',
                'weightVolume',
                'volumeLength',
                'volumeWidth',
                'volumeHeight',
                'declareValue',
                'overWeightOut',
                'valueInsured',
                'flyNo',
                'bagNo',
                'remarkSpecial',
                'remarkMember',
                'remark',
                'dataInvoice:ntext',
                'dataLabel:ntext',
                'dataError:ntext',
                'dataSuccess:ntext',
                'epl:ntext',
                'waybillPdfUrl',
                'invoicePdfUrl',
            ],
        ]);
}catch (\Exception $e){

    } ?>

</div>
