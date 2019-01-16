<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_kf_group".
 *
 * @property int $id 客服分组
 * @property string $groupname 客服分组名称
 * @property int $c_time 创建时间
 * @property string $c_user 创建人
 */
class KfGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_kf_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['groupname', 'required'],
            [['c_time'], 'integer'],
            [['groupname', 'c_user'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'groupname' => '客服分组名称',
            'c_time' => '创建时间',
            'c_user' => '创建人',
        ];
    }

    public function create($data)
    {
        if($this->load($data) && $this->validate()){
            $model = new KfGroup();
            $model->groupname = $this->groupname;
            $model->c_time = time();
            $model->c_user = Yii::$app->user->getIdentity()->username;
            if($model->save(false)){
                return true;
            }
        }
        return false;
    }

    /**
     * 获取分组数组
     * @return array
     */
    public function getOption()
    {
        $option = [null=>'尚未选择分组'];
        foreach (KfGroup::find()->all() as $group){
            $option[$group['id']] = $group['groupname'];
        }
        return $option;
    }
}
