<?php

namespace backend\models;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "{{%waybill}}".
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
 * @property int $storageId 仓库
 * @property int $countryId 国家
 * @property int $consigneeId 收件人
 * @property int $actionerId 操作人和时间
 * @property int $financeId 财务ID
 * @property int $statusId 状态
 * @property double $weightInput 入库重量
 * @property int $timeIn 入库时间
 * @property double $weightOutput 出库重量
 * @property double $weightVolume 体积重量
 * @property double $volumeLength 体积长
 * @property double $volumeWidth 体积宽
 * @property double $volumeHeight 体积高
 * @property double $declareValue 申报价值
 * @property int $overWeightOut 超重发货:0-不发货；1-发货
 * @property int $valueInsured 保价：0-不保价；1-报价
 * @property string $flyNo 航班号
 * @property string $bagNo 包号
 * @property string $remarkSpecial 特殊要求
 * @property string $remarkMember 客户备注
 * @property string $remark 备注
 * @property string $dataInvoice 发票内容
 * @property string $dataLabel 面单内容
 * @property string $dataError 官方下单错误信息
 * @property string $dataSuccess 官方下单成功信息
 * @property string $epl 佳成渠道斑马打印编码
 * @property string $waybillPdfUrl 面单pdf路径
 * @property string $invoicePdfUrl 发票pdf路径
 */
class Waybill extends \yii\db\ActiveRecord
{
    //waybill_status 表字段
    public $status;

    //waybill_consignee表字段
    public $consigneeName;  //收件人
    public $consigneeTel;   //收件人号码
    public $consigneeZip;   //收件人邮编
    public $consigneeState; //收件省州
    public $consigneeCity;  //收件城市
    public $consigneeCounty;//收件区县
    public $consigneeAddress1;//收件区县
    /**
     * 关联waybillStatus表 1：1
     */
    public function getWaybillStatus()
    {
        return $this->hasOne(WaybillStatus::className(),['waybillId' => 'id']);

    }

    /**
     * 关联waybill_actioner 表 1：1
     */
    public function getWaybillActioner()
    {
        return $this->hasOne(WaybillActioner::className(),['waybillId' => 'id']);
    }
    /**
     * 关联waybill_consignee 表 1：1
     */
    public function getWaybillConsignee()
    {
        return $this->hasOne(WaybillConsignee::className(),['waybillId' => 'id']);
    }

    /**
     * 关联物品明细表 waybill_goods 1:n
     */

    public function getWaybillGoods()
    {
        return $this->hasMany(WaybillGoods::className(),['waybillId' => 'id']);
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%waybill}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['memberId', 'channelParentId', 'channelChildId', 'storageId', 'countryId', 'consigneeId', 'actionerId', 'financeId', 'statusId', 'timeIn', 'overWeightOut', 'valueInsured'], 'integer'],
            [['weightInput', 'weightOutput', 'weightVolume', 'volumeLength', 'volumeWidth', 'volumeHeight', 'declareValue'], 'number'],
            [['timeIn'], 'required'],
            [['dataInvoice', 'dataLabel', 'dataError', 'dataSuccess', 'epl'], 'string'],
            [['codeNum', 'orderNum', 'expressNum', 'declareNum', 'memberName', 'memberCode'], 'string', 'max' => 50],
            [['flyNo', 'bagNo'], 'string', 'max' => 20],
            [['remarkSpecial', 'remark'], 'string', 'max' => 500],
            [['remarkMember'], 'string', 'max' => 100],
            [['waybillPdfUrl', 'invoicePdfUrl'], 'string', 'max' => 255],
            [['consigneeName','consigneeTel','consigneeZip','consigneeState','consigneeCity','consigneeCounty','consigneeAddress1','status',],'safe'],
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
            'storageId' => '仓库',
            'countryId' => '国家',
            'consigneeId' => '收件人',
            //'actionerId' => '操作人和时间',
            'financeId' => '财务ID',
            'statusId' => '状态',
            'weightInput' => '入库重量',
            'timeIn' => '入库时间',
            'weightOutput' => '出库重量',
            'weightVolume' => '体积重量',
            'volumeLength' => '体积长',
            'volumeWidth' => '体积宽',
            'volumeHeight' => '体积高',
            'declareValue' => '申报价值',
            'overWeightOut' => '超重发货',
            'valueInsured' => '保价',
            'flyNo' => '航班号',
            'bagNo' => '包号',
            'remarkSpecial' => '特殊要求',
            'remarkMember' => '客户备注',
            'remark' => '备注',
            'dataInvoice' => '发票内容',
            'dataLabel' => '面单内容',
            'dataError' => '官方下单错误信息',
            'dataSuccess' => '官方下单成功信息',
            'epl' => '佳成渠道斑马打印编码',
            'waybillPdfUrl' => '面单pdf路径',
            'invoicePdfUrl' => '发票pdf路径',

            'consigneeName' => '收件人',
            'consigneeTel' => '收件号码',
            'consigneeZip' => '邮编',
            'consigneeState' => '省州',
            'consigneeCity' => '城市',
            'consigneeCounty' => '区县',
            'status' => '状态'
        ];
    }

    public static function getStatus($id)
    {
        $model = WaybillStatus::find()->where(['waybillId'=>$id])->one();
        $status = '';
        if(!empty($model)){
            switch ($model->status){
                case 1:
                    $status = '未入库';break;
                case 2:
                    $status = '已入库';break;
                case 3:
                    $status = '处理中';break;
                case 4:
                    $status = '已签收';break;
                default:
                    $status = '状态异常';
            }
        }
        return $status;
    }

    /**
     * 批量修改 出入库重量 仓库
     */
    public function batchUpdate($data)
    {
        $result = ['errorCode' => 1,'errorMsg' => '操作失败'];
        $ids = $data['bidArr'];
        $res = Waybill::updateAll([$data['field']=>$data['value']],['in','id',$ids]);
        if($res){
            $result = ['errorCode' => 0,'errorMsg' => '修改成功'];
        }
        return $result;
    }

    /**
     * update waybill or waybillStatus or waybillConsignee
     */
    public function edit($data,$id)
    {
        //waybill表字段
        $waybillField = [
            'codeNum','orderNum','expressNum','declareNum','channelParentId','channelChildId','storageId', 'countryId','weightInput','weightOutput','weightVolume', 'volumeLength','volumeWidth', 'volumeHeight','declareValue','overWeightOut','valueInsured','flyNo','bagNo','remarkSpecial','remark',
        ];
        //waybillStatus 字段
        $StatusField = [
            'status',
        ];
        //waybillConsignee字段
        $consigneeField = [
            'consigneeName','consigneeTel','consigneeZip','consigneeState','consigneeCity','consigneeCounty','consigneeAddress1',
        ];

        $transaction = Yii::$app->db->beginTransaction();
        try{
            $res = true;
            //是否修改waybill字段
            if(array_intersect($waybillField,array_keys($data))){
                $waybillArr = array_intersect($waybillField,array_keys($data));
                $updateField = [];
                foreach ($waybillArr as $v){
                    $updateField[$v] = $data[$v];
                }
                $res = $res && Waybill::updateAll($updateField,['id'=>$id]);
            }
            //是否修改waybillStatus字段
            if(array_intersect($StatusField,array_keys($data))){
                $statusArr = array_intersect($StatusField,array_keys($data));
                $updateField = [];
                foreach ($statusArr as $v){
                    $updateField[$v] = $data[$v];
                }
                $res = $res && WaybillStatus::updateAll($updateField,['waybillId'=>$id]);
            }
            //是否修改了waybillConsignee字段
            if(array_intersect($consigneeField,array_keys($data))){
                $consigneeArr = array_intersect($consigneeField,array_keys($data));
                $updateField = [];
                foreach ($consigneeArr as $v){
                    $updateField[$v] = $data[$v];
                }
                $res = $res && WaybillConsignee::updateAll($updateField,['waybillId'=>$id]);
            }

            if($res){
                $transaction->commit();
                return true;
            }else{
                $transaction->rollBack();
                return false;
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new Exception($e);
        }
    }
}
