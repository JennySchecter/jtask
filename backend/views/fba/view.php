<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Fba */

$this->title = 'FBA列表详细信息';
$this->params['breadcrumbs'][] = ['label' => 'FBA列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fba-view">

    <?= DetailView::widget([
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
            'weightInput',
            'weightOutput',
            'weightVolume',
            'volumeLength',
            'volumeWidth',
            'volumeHeight',
            'declareValue',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'label' => '状态',
                'value' => function($model){
                    return \backend\models\Waybill::getStatus($model->id);
                }
            ],

            'dataInvoice:ntext',
            'dataLabel:ntext',
            'dataError:ntext',
            'dataSuccess:ntext',
        ],
    ]) ?>

</div>
