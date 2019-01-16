<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Pickup;

/**
 * PickupSearch represents the model behind the search form of `backend\models\Pickup`.
 */
class PickupSearch extends Pickup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userid', 's_time', 'e_time', 'nums', 'last_time', 'status', 'c_time'], 'integer'],
            [['username', 'address'], 'safe'],
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
        $query = Pickup::find();

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
            's_time' => $this->s_time,
            'e_time' => $this->e_time,
            'nums' => $this->nums,
            'weight' => $this->weight,
            'last_time' => $this->last_time,
            'status' => $this->status,
            'c_time' => $this->c_time,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
