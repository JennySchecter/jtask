<?php

namespace backend\models;

use backend\helpers\AdminFun;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Waybill;

/**
 * WaybillSearch represents the model behind the search form of `\backend\models\Waybill`.
 */
class WaybillSearch extends Waybill
{
    //waybill_status表相关字段
    public $status;
    public $abnormal;       //问题件(异常件)
    public $recycle;        //回收与否
    public $prerecord;      //预录单与否

    //waybill_actioner表相关字段
    public $timeCreateStart;//创建时间起
    public $timeCreateEnd;  //创建时间止
    public $time_in_start;  //入库时间起
    public $time_in_end;    //入库时间止
    public $pickUserName;   //取件人
    public $time_out_start; //出库时间起
    public $time_out_end;   //出库时间止

    //waybill_consignee表字段
    public $consigneeName;  //收件人
    public $consigneeTel;   //收件人号码
    public $consigneeZip;   //收件人邮编
    public $consigneeState; //收件省州
    public $consigneeCity;  //收件城市
    public $consigneeCounty;//收件区县

    //waybill表 相关字段
    public $weightInputStart; //入库重量起
    public $weightInputEnd;   //入库重量止
    public $weightOutputStart;//出库重量止
    public $weightOutputEnd;  //出库重量止
    public $customGroup;      //客户组别    ------批量追踪查询时使用


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'memberId', 'channelParentId', 'channelChildId', 'storageId', 'countryId', 'consigneeId', 'actionerId', 'financeId', 'statusId', 'timeIn', 'overWeightOut', 'valueInsured'], 'integer'],
            [['codeNum', 'orderNum', 'expressNum', 'declareNum', 'memberName', 'memberCode', 'flyNo', 'bagNo', 'remarkSpecial', 'remarkMember', 'remark', 'dataInvoice', 'dataLabel', 'dataError', 'dataSuccess', 'epl', 'waybillPdfUrl', 'invoicePdfUrl'], 'safe'],
            [['weightInput', 'weightOutput', 'weightVolume', 'volumeLength', 'volumeWidth', 'volumeHeight', 'declareValue'], 'number'],
            [['status','abnormal','recycle','prerecord','timeCreateStart','timeCreateEnd','time_in_start','time_in_end','pickUserName','time_out_start','time_out_end','consigneeName','consigneeTel','consigneeZip','consigneeState','consigneeCity','consigneeCounty','weightInputStart','weightInputEnd','weightOutputStart','weightOutputEnd','customGroup',],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *连表查询
     * @param array $params
     *
     * @return object
     */
    public function search($params)
    {
        $query = Waybill::find();

        $query->joinWith(['waybillStatus','waybillActioner','waybillConsignee'])->orderBy('ts_waybill.id desc');

        //客服只能看到自己分组内的运单信息
        if(AdminFun::managerOrStaff(Yii::$app->user->getId()) == 'staff'){
            $admin = Admin::findIdentity(Yii::$app->user->getId());
            $group_id = $admin['groupid'];
            $members = User::find()->where(['groupid'=>$group_id])->asArray()->all();
            $memberArr = array_map(function ($v){return $v['id'];},$members);
            $query->andWhere(['in','memberId',$memberArr]);
        }

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'memberId' => $this->memberId,
            'memberName' => $this->memberName,
            'financeId' => $this->financeId,
            'storageId' => $this->storageId,
            'countryId' => $this->countryId,
            'channelParentId' => $this->channelParentId,
            'ts_waybill_status.status' => $this->status,
            //'ts_waybill_status.recycle' => $this->recycle,
            'flyNo'=> $this->flyNo,
            'bagNo' => $this->bagNo,
        ]);
        //默认不包含回收站
        if($this->recycle == 0){
            $query->andFilterWhere(['ts_waybill_status.recycle'=>0]);
        }
        //国内异常件与否（问题件）
        if($this->abnormal == 1){
            $query->andFilterWhere(['ts_waybill_status.statusAbnormalDomestic' => 1]);
        }
        if($this->abnormal == 2){
            $query->andFilterWhere(['!=','ts_waybill_status.statusAbnormalDomestic',1]);
        }
        //入库重量筛选
        if($this->weightInputStart && $this->weightInputEnd){
            $query->andFilterWhere(['between','weightInput',$this->weightInputStart,$this->weightInputEnd]);
        }elseif($this->weightInputStart && !$this->weightInputEnd){
            $query->andFilterWhere(['>','weightInput',$this->weightInputStart]);
        }elseif(!$this->weightInputStart && $this->weightInputEnd){
            $query->andFilterWhere(['<','weightInput',$this->weightInputEnd]);
        }

        //出库重量筛选
        if($this->weightOutputStart && $this->weightOutputEnd){
            $query->andFilterWhere(['between','weightOutput',$this->weightOutputStart,$this->weightOutputEnd]);
        }elseif($this->weightOutputStart && !$this->weightOutputEnd){
            $query->andFilterWhere(['>','weightOutput',$this->weightOutputStart]);
        }elseif(!$this->weightOutputStart && $this->weightOutputEnd){
            $query->andFilterWhere(['<','weightOutput',$this->weightOutputEnd]);
        }

        //创建时间筛选
        if($this->timeCreateStart && $this->timeCreateEnd){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeCreate',strtotime($this->timeCreateStart),strtotime($this->timeCreateEnd)]);
        }elseif($this->timeCreateStart && !$this->timeCreateEnd){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeCreate',strtotime($this->timeCreateStart)]);
        }elseif(!$this->timeCreateStart && $this->timeCreateEnd){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeCreate',strtotime($this->timeCreateEnd)]);
        }
        //入库时间筛选
        if($this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeIn',strtotime($this->time_in_start),strtotime($this->time_in_end)]);
            //var_dump($query->createCommand()->getSql());die;
        }elseif($this->time_in_start && !$this->time_in_end){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeIn',strtotime($this->time_in_start)]);
        }elseif(!$this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeIn',strtotime($this->time_in_end)]);
        }
        //出库时间筛选
        if($this->time_out_start && $this->time_out_end){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeOut',strtotime($this->time_out_start),strtotime($this->time_out_end)]);
        }elseif($this->time_out_start && !$this->time_out_end){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeOut',strtotime($this->time_out_start)]);
        }elseif(!$this->time_out_start && $this->time_out_end){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeOut',strtotime($this->time_out_end)]);
        }
        $query->andFilterWhere(['like', 'codeNum', $this->codeNum])
            ->andFilterWhere(['like', 'orderNum', $this->orderNum])
            ->andFilterWhere(['like', 'expressNum', $this->expressNum])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeName', $this->consigneeName])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeTel', $this->consigneeTel])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeZip', $this->consigneeZip])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeState', $this->consigneeState])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeCity', $this->consigneeCity])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeCounty', $this->consigneeCounty])
            ->andFilterWhere(['like', 'declareNum', $this->declareNum])
            ->andFilterWhere(['like', 'memberCode', $this->memberCode])
            ->andFilterWhere(['like', 'flyNo', $this->flyNo])
            ->andFilterWhere(['like', 'bagNo', $this->bagNo]);
        //print_r($query);die;

        return $query;
    }

    /**国内异常件（问题件）查询
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function searchProblem($params)
    {
        $query = Waybill::find();

        $query->joinWith(['waybillStatus','waybillActioner','waybillConsignee'])->where(['!=','statusAbnormalDomestic',1])->orderBy('ts_waybill.id desc');

        //客服只能看到自己分组内的运单信息
        if(AdminFun::managerOrStaff(Yii::$app->user->getId()) == 'staff'){
            $admin = Admin::findIdentity(Yii::$app->user->getId());
            $group_id = $admin['groupid'];
            $members = User::find()->where(['groupid'=>$group_id])->asArray()->all();
            $memberArr = array_map(function ($v){return $v['id'];},$members);
            $query->andWhere(['in','memberId',$memberArr]);
        }

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'memberId' => $this->memberId,
            'memberName' => $this->memberName,
            'financeId' => $this->financeId,
            'storageId' => $this->storageId,
            'countryId' => $this->countryId,
            'channelParentId' => $this->channelParentId,
            'ts_waybill_status.status' => $this->status,
            //'ts_waybill_status.recycle' => $this->recycle,
            'flyNo'=> $this->flyNo,
            'bagNo' => $this->bagNo,
        ]);
        //默认不包含回收站
        if(is_null($this->recycle)){
            $query->andFilterWhere(['ts_waybill_status.recycle'=>0]);
        }else{
            $query->andFilterWhere(['ts_waybill_status.recycle'=>$this->recycle]);
        }

        //入库重量筛选
        if($this->weightInputStart && $this->weightInputEnd){
            $query->andFilterWhere(['between','weightInput',$this->weightInputStart,$this->weightInputEnd]);
        }elseif($this->weightInputStart && !$this->weightInputEnd){
            $query->andFilterWhere(['>','weightInput',$this->weightInputStart]);
        }elseif(!$this->weightInputStart && $this->weightInputEnd){
            $query->andFilterWhere(['<','weightInput',$this->weightInputEnd]);
        }

        //出库重量筛选
        if($this->weightOutputStart && $this->weightOutputEnd){
            $query->andFilterWhere(['between','weightOutput',$this->weightOutputStart,$this->weightOutputEnd]);
        }elseif($this->weightOutputStart && !$this->weightOutputEnd){
            $query->andFilterWhere(['>','weightOutput',$this->weightOutputStart]);
        }elseif(!$this->weightOutputStart && $this->weightOutputEnd){
            $query->andFilterWhere(['<','weightOutput',$this->weightOutputEnd]);
        }

        //创建时间筛选
        if($this->timeCreateStart && $this->timeCreateEnd){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeCreate',strtotime($this->timeCreateStart),strtotime($this->timeCreateEnd)]);
        }elseif($this->timeCreateStart && !$this->timeCreateEnd){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeCreate',strtotime($this->timeCreateStart)]);
        }elseif(!$this->timeCreateStart && $this->timeCreateEnd){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeCreate',strtotime($this->timeCreateEnd)]);
        }
        //入库时间筛选
        if($this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeIn',strtotime($this->time_in_start),strtotime($this->time_in_end)]);
            //var_dump($query->createCommand()->getSql());die;
        }elseif($this->time_in_start && !$this->time_in_end){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeIn',strtotime($this->time_in_start)]);
        }elseif(!$this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeIn',strtotime($this->time_in_end)]);
        }
        //出库时间筛选
        if($this->time_out_start && $this->time_out_end){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeOut',strtotime($this->time_out_start),strtotime($this->time_out_end)]);
        }elseif($this->time_out_start && !$this->time_out_end){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeOut',strtotime($this->time_out_start)]);
        }elseif(!$this->time_out_start && $this->time_out_end){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeOut',strtotime($this->time_out_end)]);
        }
        $query->andFilterWhere(['like', 'codeNum', $this->codeNum])
            ->andFilterWhere(['like', 'orderNum', $this->orderNum])
            ->andFilterWhere(['like', 'expressNum', $this->expressNum])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeName', $this->consigneeName])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeTel', $this->consigneeTel])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeZip', $this->consigneeZip])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeState', $this->consigneeState])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeCity', $this->consigneeCity])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeCounty', $this->consigneeCounty])
            ->andFilterWhere(['like', 'declareNum', $this->declareNum])
            ->andFilterWhere(['like', 'memberCode', $this->memberCode])
            ->andFilterWhere(['like', 'flyNo', $this->flyNo])
            ->andFilterWhere(['like', 'bagNo', $this->bagNo]);
        //print_r($query->asArray()->all());
        return $query;
    }

    /**预录单查询
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function searchPrerecord($params)
    {
        $query = Waybill::find();

        $query->joinWith(['waybillStatus','waybillActioner','waybillConsignee'])->orderBy('ts_waybill.id desc')->where(['status'=>1,'prerecord'=>1]);

        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'memberId' => $this->memberId,
            'memberName' => $this->memberName,
            'financeId' => $this->financeId,
            'storageId' => $this->storageId,
            'countryId' => $this->countryId,
            'channelParentId' => $this->channelParentId,
            'ts_waybill_status.status' => $this->status,
            //'ts_waybill_status.recycle' => $this->recycle,
            'flyNo'=> $this->flyNo,
            'bagNo' => $this->bagNo,
        ]);
        //包含回收站否
        if($this->recycle == 0){
            $query->andFilterWhere(['ts_waybill_status.recycle'=>$this->recycle]);
        }
        //国内异常件与否（问题件）
        if($this->abnormal == 1){
            $query->andFilterWhere(['ts_waybill_status.statusAbnormalDomestic' => 1]);
        }
        if($this->abnormal == 2){
            $query->andFilterWhere(['!=','ts_waybill_status.statusAbnormalDomestic',1]);
        }
        //入库重量筛选
        if($this->weightInputStart && $this->weightInputEnd){
            $query->andFilterWhere(['between','weightInput',$this->weightInputStart,$this->weightInputEnd]);
        }elseif($this->weightInputStart && !$this->weightInputEnd){
            $query->andFilterWhere(['>','weightInput',$this->weightInputStart]);
        }elseif(!$this->weightInputStart && $this->weightInputEnd){
            $query->andFilterWhere(['<','weightInput',$this->weightInputEnd]);
        }

        //出库重量筛选
        if($this->weightOutputStart && $this->weightOutputEnd){
            $query->andFilterWhere(['between','weightOutput',$this->weightOutputStart,$this->weightOutputEnd]);
        }elseif($this->weightOutputStart && !$this->weightOutputEnd){
            $query->andFilterWhere(['>','weightOutput',$this->weightOutputStart]);
        }elseif(!$this->weightOutputStart && $this->weightOutputEnd){
            $query->andFilterWhere(['<','weightOutput',$this->weightOutputEnd]);
        }

        //创建时间筛选
        if($this->timeCreateStart && $this->timeCreateEnd){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeCreate',strtotime($this->timeCreateStart),strtotime($this->timeCreateEnd)]);
        }elseif($this->timeCreateStart && !$this->timeCreateEnd){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeCreate',strtotime($this->timeCreateStart)]);
        }elseif(!$this->timeCreateStart && $this->timeCreateEnd){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeCreate',strtotime($this->timeCreateEnd)]);
        }
        //入库时间筛选
        if($this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeIn',strtotime($this->time_in_start),strtotime($this->time_in_end)]);
            //var_dump($query->createCommand()->getSql());die;
        }elseif($this->time_in_start && !$this->time_in_end){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeIn',strtotime($this->time_in_start)]);
        }elseif(!$this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeIn',strtotime($this->time_in_end)]);
        }
        //出库时间筛选
        if($this->time_out_start && $this->time_out_end){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeOut',strtotime($this->time_out_start),strtotime($this->time_out_end)]);
        }elseif($this->time_out_start && !$this->time_out_end){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeOut',strtotime($this->time_out_start)]);
        }elseif(!$this->time_out_start && $this->time_out_end){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeOut',strtotime($this->time_out_end)]);
        }
        $query->andFilterWhere(['like', 'codeNum', $this->codeNum])
            ->andFilterWhere(['like', 'orderNum', $this->orderNum])
            ->andFilterWhere(['like', 'expressNum', $this->expressNum])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeName', $this->consigneeName])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeTel', $this->consigneeTel])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeZip', $this->consigneeZip])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeState', $this->consigneeState])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeCity', $this->consigneeCity])
            ->andFilterWhere(['like', 'ts_waybill_consignee.consigneeCounty', $this->consigneeCounty])
            ->andFilterWhere(['like', 'declareNum', $this->declareNum])
            ->andFilterWhere(['like', 'memberCode', $this->memberCode])
            ->andFilterWhere(['like', 'flyNo', $this->flyNo])
            ->andFilterWhere(['like', 'bagNo', $this->bagNo]);

        return $query;
    }
    //查询运单超时
    public function searchOverTime($params)
    {
        $query = Waybill::find();
        $now = time();
        $query->joinWith(['waybillActioner','waybillStatus'])->orderBy('ts_waybill.id desc')->where(['recycle'=>0]);//未删除的
        $query->andWhere(['or',
            [
                'and',
                ['status'=>2],
                ['timeUpdate'=>0],
                ['<','ts_waybill_actioner.timeIn',$now-86400]
            ],         //入库 但24小时内没有补全
            [
                'and',
                ['status'=>3],
                ['timeOut'=>0],
                ['<','timeUpdate',$now-86400],  //补全操作后24小时未出库
            ],
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'memberId' => $this->memberId,
            'channelParentId' => $this->channelParentId,
            'channelChildId' => $this->channelChildId,
            'statusId' => $this->statusId,
            'weightInput' => $this->weightInput,
            //'timeIn' => $this->timeIn,
            'ts_waybill_status.status' => $this->status
        ]);

//        $time_in_start = $this->time_in_start? strtotime($this->time_in_start):'';
//        $time_in_end = $this->time_in_end? strtotime($this->time_in_end):'';

        if($this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['between', 'ts_waybill_actioner.timeIn', strtotime($this->time_in_start),strtotime($this->time_in_end)]);
        }elseif($this->time_in_start && !$this->time_in_end){
            $query->andFilterWhere(['>', 'ts_waybill_actioner.timeIn', strtotime($this->time_in_start)]);
        }elseif(!$this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['<', 'ts_waybill_actioner.timeIn', strtotime($this->time_in_end)]);
        }

        $query->andFilterWhere(['like', 'codeNum', $this->codeNum])
            ->andFilterWhere(['like', 'orderNum', $this->orderNum])
            ->andFilterWhere(['like', 'expressNum', $this->expressNum])
            ->andFilterWhere(['like', 'declareNum', $this->declareNum])
            ->andFilterWhere(['like', 'memberName', $this->memberName])
            ->andFilterWhere(['like', 'memberCode', $this->memberCode])
            ->andFilterWhere(['like', 'flyNo', $this->flyNo])
            ->andFilterWhere(['like', 'bagNo', $this->bagNo]);
        //var_dump($query->all());die;
        return $dataProvider;
    }

    //导出超时运单
    public function exportovertime($params)
    {
        $query = Waybill::find();
        $now = time();
        $query->joinWith(['waybillActioner','waybillStatus'])->where(['recycle'=>0]);//未删除的
        $query->andWhere(['or',
            [
                'and',
                ['status'=>2],
                ['timeUpdate'=>0],
                ['<','ts_waybill_actioner.timeIn',$now-86400]
            ],         //入库 但24小时内没有补全
            [
                'and',
                ['status'=>3],
                ['timeOut'=>0],
                ['<','timeUpdate',$now-86400],  //补全操作后24小时未出库
            ],
        ]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'memberId' => $this->memberId,
            'channelParentId' => $this->channelParentId,
            'channelChildId' => $this->channelChildId,
            'statusId' => $this->statusId,
            'weightInput' => $this->weightInput,
            //'timeIn' => $this->timeIn,
            'ts_waybill_status.status' => $this->status,
        ]);

        $time_in_start = $this->time_in_start? strtotime($this->time_in_start):'';
        $time_in_end = $this->time_in_end? strtotime($this->time_in_end):'';

        if($this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['between', 'ts_waybill_actioner.timeIn', $time_in_start,$time_in_end]);
        }elseif($this->time_in_start && !$this->time_in_end){
            $query->andFilterWhere(['>', 'ts_waybill_actioner.timeIn', $time_in_start]);
        }elseif(!$this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['<', 'ts_waybill_actioner.timeIn', $time_in_end]);
        }

        $query->andFilterWhere(['like', 'codeNum', $this->codeNum])
            ->andFilterWhere(['like', 'orderNum', $this->orderNum])
            ->andFilterWhere(['like', 'expressNum', $this->expressNum])
            ->andFilterWhere(['like', 'declareNum', $this->declareNum])
            ->andFilterWhere(['like', 'memberName', $this->memberName])
            ->andFilterWhere(['like', 'memberCode', $this->memberCode])
            ->andFilterWhere(['like', 'flyNo', $this->flyNo])
            ->andFilterWhere(['like', 'bagNo', $this->bagNo]);
        return $query->all();
    }

    /**运单追踪的筛选
     * @param $queryParams
     * @return \yii\db\ActiveQuery
     */
    public function searchTrace($queryParams)
    {
        $query = Waybill::find();
        $query->joinWith(['waybillActioner','waybillStatus'])->where(['!=','expressNum','']);
        $this->load($queryParams);

        $query->andFilterWhere([
            'memberName' => $this->memberName,
            'storageId' => $this->storageId,
            'countryId' => $this->countryId,
            'channelParentId' => $this->channelParentId,
            'ts_waybill_status.status' => $this->status,
        ]);

        //客户组别筛选
        if($this->customGroup){
            $memberArr = User::find()->where(['groupid'=>$this->customGroup])->asArray()->all();
            $memberArr = array_map(function ($v){return $v['id'];},$memberArr);
            $query->andFilterWhere(['in','memberId',$memberArr]);
        }

        //入库时间筛选
        if($this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeIn',strtotime($this->time_in_start),strtotime($this->time_in_end)]);
            //var_dump($query->createCommand()->getSql());die;
        }elseif($this->time_in_start && !$this->time_in_end){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeIn',strtotime($this->time_in_start)]);
        }elseif(!$this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeIn',strtotime($this->time_in_end)]);
        }
        //出库时间筛选
        if($this->time_out_start && $this->time_out_end){
            $query->andFilterWhere(['between','ts_waybill_actioner.timeOut',strtotime($this->time_out_start),strtotime($this->time_out_end)]);
        }elseif($this->time_out_start && !$this->time_out_end){
            $query->andFilterWhere(['>','ts_waybill_actioner.timeOut',strtotime($this->time_out_start)]);
        }elseif(!$this->time_out_start && $this->time_out_end){
            $query->andFilterWhere(['<','ts_waybill_actioner.timeOut',strtotime($this->time_out_end)]);
        }

        $query->andFilterWhere(['like','orderNum',$this->orderNum])
              ->andFilterWhere(['like','codeNum',$this->codeNum])
              ->andFilterWhere(['like','flyNo',$this->flyNo])
              ->andFilterWhere(['like','bagNo',$this->bagNo]);
        //var_dump($query->asArray()->all());die;
        return $query;
    }
}
