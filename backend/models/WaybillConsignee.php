<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_waybill_consignee".
 *
 * @property int $id
 * @property int $waybillId 运单
 * @property string $consigneeName 收件人
 * @property string $consigneeTel 收件电话
 * @property string $consigneeMobile 收件手机号
 * @property string $consigneeCountry 收件国家
 * @property string $consigneeState 收件省州
 * @property string $consigneeCity 收件城市
 * @property string $consigneeCounty 收件区县
 * @property string $consigneeZip 邮编
 * @property string $consigneeAddress1 收件地址1
 * @property string $consigneeAddress2 收件地址2
 * @property string $consigneeAddress3 收件地址3
 */
class WaybillConsignee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_waybill_consignee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['waybillId'], 'integer'],
            [['consigneeZip'], 'required'],
            [['consigneeName', 'consigneeAddress1', 'consigneeAddress2', 'consigneeAddress3'], 'string', 'max' => 50],
            [['consigneeTel'], 'string', 'max' => 20],
            [['consigneeMobile', 'consigneeCountry'], 'string', 'max' => 15],
            [['consigneeState', 'consigneeCity', 'consigneeCounty'], 'string', 'max' => 30],
            [['consigneeZip'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'waybillId' => 'Waybill ID',
            'consigneeName' => 'Consignee Name',
            'consigneeTel' => 'Consignee Tel',
            'consigneeMobile' => 'Consignee Mobile',
            'consigneeCountry' => 'Consignee Country',
            'consigneeState' => 'Consignee State',
            'consigneeCity' => 'Consignee City',
            'consigneeCounty' => 'Consignee County',
            'consigneeZip' => 'Consignee Zip',
            'consigneeAddress1' => 'Consignee Address1',
            'consigneeAddress2' => 'Consignee Address2',
            'consigneeAddress3' => 'Consignee Address3',
        ];
    }
}
