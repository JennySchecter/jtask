<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Fba;
use yii\db\Query;

/**
 * FbaSearch represents the model behind the search form of `backend\models\Fba`.
 */
class FbaSearch extends Fba
{
    public $weightInputStart;   //入库重量起
    public $weightInputEnd;     //入库重量止
    public $weightOutputStart;  //出库重量起
    public $weightOutputEnd;    //出库重量止
    public $timeCreateStart;    //创建时间起
    public $timeCreateEnd;      //创建时间止
    public $time_in_start;      //入库时间起
    public $time_in_end;        //入库时间止
    public $time_out_start;     //出库时间起
    public $time_out_end;       //出库时间止
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'memberId', 'channelParentId', 'channelChildId', 'consigneeId', 'storageId', 'countryId', 'userCreate', 'timeCreate', 'userIn', 'timeIn', 'userUpdate', 'timeUpdate', 'userExpress', 'timeExpress', 'userModify', 'timeModify', 'userOut', 'timeOut', 'timeSign', 'userMerge', 'timeMerge', 'pickUserId', 'pickUserName', 'status', 'statusAbnormalDomestic', 'statusAbnormalForeign', 'financeCheck', 'financeWriteoff', 'recycle', 'overWeightOut', 'valueInsured'], 'integer'],
            [['codeNum', 'orderNum', 'expressNum', 'declareNum', 'memberName', 'memberCode', 'flyNo', 'bagNo', 'remark', 'statusAbnormalRemark', 'financeWriteoffNo', 'dataInvoice', 'dataLabel', 'dataError', 'dataSuccess'], 'safe'],
            [['weightInput', 'weightOutput', 'weightVolume', 'volumeLength', 'volumeWidth', 'volumeHeight', 'declareValue', 'amountWaybill', 'amountPaied', 'amountSupplier', 'financeWriteoffMoney'], 'number'],
            [['weightInputStart','weightInputEnd','weightOutputStart','weightOutputEnd','timeCreateStart','timeCreateEnd','time_in_start','time_in_end','time_out_start','time_out_end'],'safe'],
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
     * @param $params
     * @return \yii\db\ActiveQuery
     */
    public function search($params)
    {
        $query = Fba::find();
        //默认筛选未删除的FBA
        $query->where(['recycle'=>0])->orderBy('id desc');
        $this->load($params);

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
            $query->andFilterWhere(['between','timeCreate',strtotime($this->timeCreateStart),strtotime($this->timeCreateEnd)]);
        }elseif($this->timeCreateStart && !$this->timeCreateEnd){
            $query->andFilterWhere(['>','timeCreate',strtotime($this->timeCreateStart)]);
        }elseif(!$this->timeCreateStart && $this->timeCreateEnd){
            $query->andFilterWhere(['<','timeCreate',strtotime($this->timeCreateEnd)]);
        }

        //入库时间筛选
        if($this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['between','timeIn',strtotime($this->time_in_start),strtotime($this->time_in_end)]);
        }elseif($this->time_in_start && !$this->time_in_end){
            $query->andFilterWhere(['>','timeIn',strtotime($this->time_in_start)]);
        }elseif(!$this->time_in_start && $this->time_in_end){
            $query->andFilterWhere(['<','timeIn',strtotime($this->time_in_end)]);
        }

        //出库时间筛选
        if($this->time_out_start && $this->time_out_end){
            $query->andFilterWhere(['between','timeOut',strtotime($this->time_out_start),strtotime($this->time_out_end)]);
        }elseif($this->time_out_start && !$this->time_out_end){
            $query->andFilterWhere(['>','timeOut',strtotime($this->time_out_start)]);
        }elseif(!$this->time_out_start && $this->time_out_end){
            $query->andFilterWhere(['<','timeOut',strtotime($this->time_out_end)]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'memberName'=>$this->memberName,
            'channelParentId' => $this->channelParentId,
            'storageId' => $this->storageId,
            'countryId' => $this->countryId,
            'pickUserId' => $this->pickUserId,
            'pickUserName' => $this->pickUserName,
            'status' => $this->status,
            //'statusAbnormalDomestic' => $this->statusAbnormalDomestic,
            //'statusAbnormalForeign' => $this->statusAbnormalForeign,
            //'recycle' => $this->recycle,
        ]);

        $query->andFilterWhere(['like', 'codeNum', $this->codeNum])
            ->andFilterWhere(['like', 'orderNum', $this->orderNum])
            ->andFilterWhere(['like', 'expressNum', $this->expressNum])
            ->andFilterWhere(['like', 'declareNum', $this->declareNum])
            ->andFilterWhere(['like', 'flyNo', $this->flyNo])
            ->andFilterWhere(['like', 'bagNo', $this->bagNo])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'statusAbnormalRemark', $this->statusAbnormalRemark])
            ->andFilterWhere(['like', 'financeWriteoffNo', $this->financeWriteoffNo])
            ->andFilterWhere(['like', 'dataInvoice', $this->dataInvoice])
            ->andFilterWhere(['like', 'dataLabel', $this->dataLabel])
            ->andFilterWhere(['like', 'dataError', $this->dataError])
            ->andFilterWhere(['like', 'dataSuccess', $this->dataSuccess]);

        return $query;
    }
}
