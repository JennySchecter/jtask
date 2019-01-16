<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_waybill_goods".
 *
 * @property int $id
 * @property int $waybillId 所属运单ID
 * @property string $nameCn 物品名称
 * @property string $nameEn 英文名
 * @property string $hsCode 海关编码
 * @property double $price 单价
 * @property int $quantity 数量
 * @property double $weight 重量
 */
class WaybillGoods extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_waybill_goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['waybillId', 'quantity'], 'integer'],
            [['price', 'weight'], 'number'],
            [['nameCn', 'nameEn'], 'string', 'max' => 50],
            [['hsCode'], 'string', 'max' => 15],
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
            'nameCn' => 'Name Cn',
            'nameEn' => 'Name En',
            'hsCode' => 'Hs Code',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'weight' => 'Weight',
        ];
    }
}
