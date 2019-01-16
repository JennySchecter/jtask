<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%group}}".
 *
 * @property int $id 客户分组表
 * @property string $groupname 分组名称
 * @property int $createtime 创建时间
 * @property int $updatetime
 * @property string $creatby 创建人
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['groupname','required','message'=>'组名不能为空'],
            [['createtime', 'updatetime'], 'integer'],
            [['groupname'], 'string', 'max' => 60],
            [['creatby'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'groupname' => '分组名称',
            'createtime' => '创建时间',
            'updatetime' => '更新时间',
            'creatby' => '创建人',
        ];
    }

    /**
     * 获取分组数组
     * @return array
     */
    public function getOption()
    {
        $option = [null=>'尚未选择分组'];
        foreach (Group::find()->all() as $group){
            $option[$group['id']] = $group['groupname'];
        }
        return $option;
    }

    /**
     * create group
     */
    public function create($data)
    {
        $admin = Admin::findIdentity(yii::$app->user->getId());
        if($this->load($data) && $this->validate()){
            $model = new Group();
            $model->groupname = $this->groupname;
            $model->createtime = time();
            $model->creatby = $admin->username;

            if($model->save(false)){
                return true;
            }
        }
        return false;
    }

    /**获取客户组别
     * @return array
     */
    public static function dropDrownList()
    {
        $query = static::find();
        $enums = $query->all();
        $list[null] = '请选择';
        $enums = ArrayHelper::map($enums,'id','groupname');
        foreach ($enums as $v){
            array_push($list,$v);
        }
        return $list;
    }
}
