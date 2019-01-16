<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\WaybillProblem;

/**
 * WaybillProblemSearch represents the model behind the search form of `backend\models\WaybillProblem`.
 */
class WaybillProblemSearch extends WaybillProblem
{
    //筛选开始时间、结束时间
    public $s_time;
    public $e_time;
    //运单号、订单号、转单号
    public $codeNum;
    public $orderNum;
    public $expressNum;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'waybillId', 'deal_status', 'c_time', 'up_time', 'create_id'], 'integer'],
            [['remark', 'create_user'], 'safe'],
            [['s_time','e_time','codeNum','orderNum','expressNum'],'safe'],
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WaybillProblem::find();
        $query->joinWith(['waybill'])->orderBy('c_time desc');

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
            'waybillId' => $this->waybillId,
            'deal_status' => $this->deal_status,
            'c_time' => $this->c_time,
            'up_time' => $this->up_time,
            'create_id' => $this->create_id,
            'ts_waybill.codeNum' => $this->codeNum,
            'ts_waybill.orderNum' => $this->orderNum,
            'ts_waybill.expressNum' => $this->expressNum,
        ]);

        $start_time = $this->s_time ? strtotime($this->s_time):'';
        $end_time = $this->e_time ? strtotime($this->e_time):'';
        $query->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['between', 'c_time',$start_time,$end_time])
            ->andFilterWhere(['like', 'create_user', $this->create_user]);
        
        return $dataProvider;
    }

    /**
     * 问题件导出
     * @param $params
     * @return array|\yii\db\ActiveRecord[]
     */
    public function export($params)
    {
        $query = WaybillProblem::find();
        $query->joinWith(['waybill']);

        // add conditions that should always apply here

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'waybillId' => $this->waybillId,
            'deal_status' => $this->deal_status,
            'c_time' => $this->c_time,
            'up_time' => $this->up_time,
            'create_id' => $this->create_id,
            'ts_waybill.codeNum' => $this->codeNum,
            'ts_waybill.orderNum' => $this->orderNum,
            'ts_waybill.expressNum' => $this->expressNum,
        ]);

        $start_time = $this->s_time ? strtotime($this->s_time):'';
        $end_time = $this->e_time ? strtotime($this->e_time):'';
        $query->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['between', 'c_time',$start_time,$end_time])
            ->andFilterWhere(['like', 'create_user', $this->create_user]);

        return $query->all();
    }
}
