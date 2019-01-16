<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%investigate_type}}".
 *
 * @property int $id
 * @property string $dc_name 调查类型名称
 * @property string $create_user 创建人
 * @property int $c_time 创建时间
 */
class InvestigateType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%investigate_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dc_name'], 'required'],
            [['c_time'], 'integer'],
            [['dc_name'], 'string', 'max' => 100],
            [['create_user'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dc_name' => '调查类型名称',
            'create_user' => '创建人',
            'c_time' => '创建时间',
        ];
    }

    public function create($data)
    {
       if($this->load($data) && $this->validate()){
            $admin = Admin::findIdentity(Yii::$app->user->getId());
            $model = new InvestigateType();
            $model->dc_name = $this->dc_name;
            $model->c_time = time();
            $model->create_user = $admin['username'];

            if($model->save(false)){
                return true;
            }
       }
       return false;
    }

    public static function dropDrownList()
    {
        $query = static::find();
        $enums = $query->all();
        return $enums? ArrayHelper::map($enums,'id','dc_name'):[];
    }
}
