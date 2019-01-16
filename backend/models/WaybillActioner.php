<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%waybill_actioner}}".
 *
 * @property int $id
 * @property int $waybillId 运单
 * @property int $userCreate 创建人(0-非内部创建)
 * @property int $timeCreate 创建时间
 * @property int $userIn 入库人
 * @property int $timeIn 入库时间
 * @property int $userUpdate 补全人
 * @property int $timeUpdate 补全时间
 * @property int $userExpress 出单人
 * @property int $timeExpress 出单时间
 * @property int $userModify 修改人
 * @property int $timeModify 修改时间
 * @property int $userOut 出库人
 * @property int $timeOut 出库时间
 * @property int $timeSign 签收时间
 * @property int $userMerge 集包人
 * @property int $timeMerge 集包时间
 * @property int $mergeId 集包大包ID
 * @property int $pickUserId 取件人ID
 * @property string $pickUserName 取件人姓名
 */
class WaybillActioner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%waybill_actioner}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['waybillId', 'userCreate', 'timeCreate', 'userIn', 'timeIn', 'userUpdate', 'timeUpdate', 'userExpress', 'timeExpress', 'userModify', 'timeModify', 'userOut', 'timeOut', 'timeSign', 'userMerge', 'timeMerge', 'mergeId', 'pickUserId'], 'integer'],
            [['pickUserName'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'waybillId' => '运单',
            'userCreate' => '创建人(0-非内部创建)',
            'timeCreate' => '创建时间',
            'userIn' => '入库人',
            'timeIn' => '入库时间',
            'userUpdate' => '补全人',
            'timeUpdate' => '补全时间',
            'userExpress' => '出单人',
            'timeExpress' => '出单时间',
            'userModify' => '修改人',
            'timeModify' => '修改时间',
            'userOut' => '出库人',
            'timeOut' => '出库时间',
            'timeSign' => '签收时间',
            'userMerge' => '集包人',
            'timeMerge' => '集包时间',
            'mergeId' => '集包大包ID',
            'pickUserId' => '取件人ID',
            'pickUserName' => '取件人姓名',
        ];
    }
}
