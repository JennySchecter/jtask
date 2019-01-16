<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SurveyList;

/**
 * SurveyListSearch represents the model behind the search form of `backend\models\SurveyList`.
 */
class SurveyListSearch extends SurveyList
{
    public $s_time;//创建日期始
    public $e_time;//创建日期终
    public $ns_time;//下次联系时间始
    public $ne_time;//下次联系时间终
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'it_id', 'dc_num', 'order_num', 'dc_channel', 'c_time', 'isadd', 'deal_time', 'next_time', 'deal_num', 'status', 'dc_result', 'undertake', 'file_time'], 'integer'],
            [['member_name','channelParentId','channelChildId', 'description', 'create_user', 'feedback', 'deal_user', 'undertake_name', 'file_user'], 'safe'],
            [['compensate_money'], 'number'],
            [['s_time','e_time','ns_time','ne_time'],'safe'],
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

    /*
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SurveyList::find();

        $query->orderBy('isadd desc');
        //默认显示未处理的工单,和处理中已追加的置顶
        if(empty($params['SurveyListSearch'])){
            //$query->where(['status'=>0]);
            $query->where(['or',['status'=>0],['isadd'=>1]]);
        }
        $this->load($params);
        // grid filtering conditions
        $query->andFilterWhere([
            'it_id' => $this->it_id,
            'dc_num' => $this->dc_num,
            'order_num' => $this->order_num,
            'dc_channel' => $this->dc_channel,
            'status' => $this->status,
            'channelParentId'=>$this->channelParentId,
            'channelChildId'=>$this->channelChildId,
        ]);

        $start_time = $this->s_time? strtotime($this->s_time):'';
        $end_time = $this->e_time? strtotime($this->e_time):'';
        $next_start_time = $this->ns_time? strtotime($this->ns_time):'';
        $next_end_time = $this->ne_time? strtotime($this->ne_time):'';
        if($this->s_time && $this->e_time){
            $query->andFilterWhere(['between', 'c_time', $start_time,$end_time]);
        }elseif($this->s_time && !$this->e_time){
            $query->andFilterWhere(['>', 'c_time', $start_time]);
        }elseif(!$this->s_time && $this->e_time){
            $query->andFilterWhere(['<', 'c_time', $end_time]);
        }

        if($this->ns_time && $this->ne_time){
            $query->andFilterWhere(['between', 'next_time', $next_start_time,$next_end_time]);
        }elseif($this->ns_time && !$this->ne_time){
            $query->andFilterWhere(['>', 'next_time', $next_start_time]);
        }elseif(!$this->ns_time && $this->ne_time){
            $query->andFilterWhere(['<', 'next_time', $next_end_time]);
        }
        $query->andFilterWhere(['like', 'member_name', $this->member_name]);


        return $query;
    }
}
