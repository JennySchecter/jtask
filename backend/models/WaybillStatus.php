<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%waybill_status}}".
 *
 * @property int $id
 * @property int $waybillId 运单
 * @property int $status 运单状态：1-未入库；2-已入库；3-处理中；4-已出库；5-已签收
 * @property int $statusAbnormalDomestic 异常件（国内）状态：1-正常件；2-已丢件；8-已退回；9-已销毁
 * @property int $statusAbnormalForeign 异常件（国外）状态：1-正常件；2-已丢件；8-已退回；9-已销毁
 * @property string $statusAbnormalRemark 异常件备注
 * @property int $comeFrom 来源：999-后台；1-客户；2-接口；3-E商赢(抓取)；4-E商赢(接口)；5-通途；6-普原；7-店小秘；8-马帮；9-华磊；10-芒果；11-通途新接口
 * @property int $reserve 预留单：0-非预留单；1-预留单
 * @property int $recycle 删除：0-未删除；1-已删除
 * @property int $expressStatus 单号申请状态：1-未申请；2-已申请
 */
class WaybillStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%waybill_status}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['waybillId', 'status', 'statusAbnormalDomestic', 'statusAbnormalForeign', 'comeFrom', 'reserve', 'recycle', 'expressStatus','prerecord'], 'integer'],
            [['statusAbnormalRemark'], 'string', 'max' => 200],
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
            'status' => '运单状态：1-未入库；2-已入库；3-处理中；4-已出库；5-已签收',
            'statusAbnormalDomestic' => '异常件（国内）状态：1-正常件；2-已丢件；8-已退回；9-已销毁',
            'statusAbnormalForeign' => '异常件（国外）状态：1-正常件；2-已丢件；8-已退回；9-已销毁',
            'statusAbnormalRemark' => '异常件备注',
            'comeFrom' => '来源：999-后台；1-客户；2-接口；3-E商赢(抓取)；4-E商赢(接口)；5-通途；6-普原；7-店小秘；8-马帮；9-华磊；10-芒果；11-通途新接口',
            'reserve' => '预留单：0-非预留单；1-预留单',
            'recycle' => '删除：0-未删除；1-已删除',
            'expressStatus' => '单号申请状态：1-未申请；2-已申请',
        ];
    }

    /**
     * 批量修改 状态
     */
    public function batchUpdate($data)
    {
        $result = ['errorCode' => 1,'errorMsg' => '操作失败'];
        $ids = $data['bidArr'];
        $res = WaybillStatus::updateAll([$data['field']=>$data['value']],['in','waybillId',$ids]);
        if($res){
            $result = ['errorCode' => 0,'errorMsg' => '修改成功'];
        }
        return $result;
    }
}
