<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\UserSend;

/**
 * UserSendSearch represents the model behind the search form of `backend\models\UserSend`.
 */
class UserSendSearch extends UserSend
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userid', 'nums', 'status', 'c_time', 'up_time', 'handleId'], 'integer'],
            [['username', 'company', 'expressNum', 'sendername', 'sendermobile', 'item', 'memremark', 'remark'], 'safe'],
            [['weight'], 'number'],
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
        $query = UserSend::find();

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
            'userid' => $this->userid,
            'nums' => $this->nums,
            'weight' => $this->weight,
            'status' => $this->status,
            'c_time' => $this->c_time,
            'up_time' => $this->up_time,
            'handleId' => $this->handleId,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'expressNum', $this->expressNum])
            ->andFilterWhere(['like', 'sendername', $this->sendername])
            ->andFilterWhere(['like', 'sendermobile', $this->sendermobile])
            ->andFilterWhere(['like', 'item', $this->item])
            ->andFilterWhere(['like', 'memremark', $this->memremark])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
