<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_fba".
 *
 * @property int $id
 * @property string $codeNum 运单号
 * @property string $orderNum 订单号
 * @property string $expressNum 转单号
 * @property string $declareNum 报关号
 * @property int $memberId 客户ID
 * @property string $memberName 客户名称
 * @property string $memberCode 客户简码
 * @property int $channelParentId 父渠道
 * @property int $channelChildId 子渠道
 * @property int $consigneeId
 * @property int $storageId 仓库
 * @property int $countryId 目的国家
 * @property double $weightInput 入库重量
 * @property double $weightOutput 出库重量
 * @property double $weightVolume 体积重量
 * @property double $volumeLength 体积长
 * @property double $volumeWidth 体积宽
 * @property double $volumeHeight 体积高
 * @property double $declareValue 申报价值
 * @property double $amountWaybill 运费
 * @property double $amountPaied 已付费用
 * @property double $amountSupplier 成本价
 * @property string $flyNo 航班号
 * @property string $bagNo 包号
 * @property string $remark 备注
 * @property int $userCreate 创建人
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
 * @property int $pickUserId 取件人ID
 * @property int $pickUserName 取件人姓名
 * @property int $status 运单状态：1-未入库；2-已入库；3-处理中；4-已出库；5-已签收
 * @property int $statusAbnormalDomestic 异常件（国内）状态：1-正常件；2-已丢件；8-已退回；9-已销毁
 * @property int $statusAbnormalForeign 异常件（国外）状态：1-正常件；2-已丢件；8-已退回；9-已销毁
 * @property string $statusAbnormalRemark 异常件备注
 * @property int $financeCheck 财务审核：0-未审核；1-已审核
 * @property int $financeWriteoff 核销：0-未核销；1-已核销
 * @property string $financeWriteoffNo 核销单号
 * @property double $financeWriteoffMoney 核销金额
 * @property int $recycle 删除：0-未删除；1-已删除
 * @property string $dataInvoice 发票内容
 * @property string $dataLabel 面单内容
 * @property string $dataError 官方下单错误信息
 * @property string $dataSuccess 官方下单成功信息
 * @property int $overWeightOut 超重发货
 * @property int $valueInsured 保价
 */
class Fba extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_fba';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['memberId', 'channelParentId', 'channelChildId', 'consigneeId', 'storageId', 'countryId', 'userCreate', 'timeCreate', 'userIn', 'timeIn', 'userUpdate', 'timeUpdate', 'userExpress', 'timeExpress', 'userModify', 'timeModify', 'userOut', 'timeOut', 'timeSign', 'userMerge', 'timeMerge', 'pickUserId', 'pickUserName', 'status', 'statusAbnormalDomestic', 'statusAbnormalForeign', 'financeCheck', 'financeWriteoff', 'recycle', 'overWeightOut', 'valueInsured'], 'integer'],
            [['weightInput', 'weightOutput', 'weightVolume', 'volumeLength', 'volumeWidth', 'volumeHeight', 'declareValue', 'amountWaybill', 'amountPaied', 'amountSupplier', 'financeWriteoffMoney'], 'number'],
            [['pickUserName'], 'required'],
            [['dataInvoice', 'dataLabel', 'dataError', 'dataSuccess'], 'string'],
            [['codeNum', 'orderNum', 'expressNum', 'declareNum', 'memberName', 'memberCode'], 'string', 'max' => 50],
            [['flyNo', 'bagNo'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 500],
            [['statusAbnormalRemark'], 'string', 'max' => 200],
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
            'codeNum' => '运单号',
            'orderNum' => '订单号',
            'expressNum' => '转单号',
            'declareNum' => '报关号',
            'memberId' => '客户ID',
            'memberName' => '客户名称',
            'memberCode' => '客户简码',
            'channelParentId' => '父渠道',
            'channelChildId' => '子渠道',
            //'consigneeId' => 'Consignee ID',
            'storageId' => '仓库',
            'countryId' => '国家',
            'weightInput' => '入库重量',
            'weightOutput' => '出库重量',
            'weightVolume' => '体积重量',
            'volumeLength' => '体积长',
            'volumeWidth' => '体积宽',
            'volumeHeight' => '体积高',
            'declareValue' => '申报价值',
            'amountWaybill' => '运费',
            'amountPaied' => '已付费用',
            'amountSupplier' => '成本价',
            'flyNo' => '航班号',
            'bagNo' => '包号',
            'remark' => '备注',
            'userCreate' => '创建人',
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
            'pickUserId' => '取件人',
            'pickUserName' => '取件人姓名',
            'status' => '运单状态',
            'statusAbnormalDomestic' => '异常件国内状态',
            'statusAbnormalForeign' => '异常件国外状态',
            'statusAbnormalRemark' => '异常件备注',
            'financeCheck' => '财务审核',
            'financeWriteoff' => '核销',
            'financeWriteoffNo' => '核销单号',
            'financeWriteoffMoney' => '核销金额',
            'recycle' => '删除',
            'dataInvoice' => '发票内容',
            'dataLabel' => '面单内容',
            'dataError' => '官方下单错误信息',
            'dataSuccess' => '官方下单成功信息',
            'overWeightOut' => '超重发货',
            'valueInsured' => '超重发货',
        ];
    }
}
