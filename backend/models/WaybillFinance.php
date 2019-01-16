<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_waybill_finance".
 *
 * @property int $id
 * @property int $waybillId 运单
 * @property double $amountWaybill 运费
 * @property double $amountPaied 已付运费
 * @property double $amountCost 成本价
 * @property int $financeCheck 财务审核：0-未审核；1-已审核
 * @property int $financeWriteoff 核销：0-未核销；1-已核销
 * @property string $financeWriteoffNo 核销单号
 * @property double $financeWriteoffMoney 核销金额
 */
class WaybillFinance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_waybill_finance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['waybillId', 'financeCheck', 'financeWriteoff'], 'integer'],
            [['amountWaybill', 'amountPaied', 'amountCost', 'financeWriteoffMoney'], 'number'],
            [['financeWriteoffNo'], 'string', 'max' => 32],
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
            'amountWaybill' => 'Amount Waybill',
            'amountPaied' => 'Amount Paied',
            'amountCost' => 'Amount Cost',
            'financeCheck' => 'Finance Check',
            'financeWriteoff' => 'Finance Writeoff',
            'financeWriteoffNo' => 'Finance Writeoff No',
            'financeWriteoffMoney' => 'Finance Writeoff Money',
        ];
    }
}
