<?php

namespace backend\models;

use backend\helpers\AdminFun;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\User;

/**
 * UserSearch represents the model behind the search form of `backend\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'role', 'created_at', 'updated_at', 'payType', 'authSimple', 'authReturnApiError', 'storageId', 'department', 'status', 'groupid', 'isvip'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'name', 'code', 'mobile', 'wechat', 'qq', 'address', 'address2', 'address3', 'address4', 'address5', 'paperworkCode', 'paperworkCode2', 'goodsName', 'goodsEnglish', 'goodsCode', 'special', 'contract', 'apiKey', 'esyUser', 'esyPass', 'token'], 'safe'],
            [['balance', 'balance1', 'balance2', 'balance3', 'creditMoney', 'goodsPrice'], 'number'],
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
        $query = User::find()->orderBy('id desc');

        //如果是客服只能看到自己服务组的客户信息

        //var_dump(AdminFun::managerOrStaff(Yii::$app->user->getId()));die;
        if(AdminFun::managerOrStaff(Yii::$app->user->getId()) == 'staff'){
            $admin = Admin::findIdentity(Yii::$app->user->getId());
            $group_id = $admin['groupid'];
            //如果该客服尚未分配服务分组，则看不到任何客户的信息
            if($group_id == ''){
                $query->where(['groupid'=>'-1']);
            }else{
                $query->where(['groupid'=>$group_id]);
            }
        }

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
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'balance' => $this->balance,
            'balance1' => $this->balance1,
            'balance2' => $this->balance2,
            'balance3' => $this->balance3,
            'creditMoney' => $this->creditMoney,
            'goodsPrice' => $this->goodsPrice,
            'payType' => $this->payType,
            'authSimple' => $this->authSimple,
            'authReturnApiError' => $this->authReturnApiError,
            'storageId' => $this->storageId,
            'department' => $this->department,
            'status' => $this->status,
            'groupid' => $this->groupid,
            'isvip' => $this->isvip,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'wechat', $this->wechat])
            ->andFilterWhere(['like', 'qq', $this->qq])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'address2', $this->address2])
            ->andFilterWhere(['like', 'address3', $this->address3])
            ->andFilterWhere(['like', 'address4', $this->address4])
            ->andFilterWhere(['like', 'address5', $this->address5])
            ->andFilterWhere(['like', 'paperworkCode', $this->paperworkCode])
            ->andFilterWhere(['like', 'paperworkCode2', $this->paperworkCode2])
            ->andFilterWhere(['like', 'goodsName', $this->goodsName])
            ->andFilterWhere(['like', 'goodsEnglish', $this->goodsEnglish])
            ->andFilterWhere(['like', 'goodsCode', $this->goodsCode])
            ->andFilterWhere(['like', 'special', $this->special])
            ->andFilterWhere(['like', 'contract', $this->contract])
            ->andFilterWhere(['like', 'apiKey', $this->apiKey])
            ->andFilterWhere(['like', 'esyUser', $this->esyUser])
            ->andFilterWhere(['like', 'esyPass', $this->esyPass])
            ->andFilterWhere(['like', 'token', $this->token]);

        return $dataProvider;
    }
}
