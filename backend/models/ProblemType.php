<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%problem_type}}".
 *
 * @property int $id
 * @property string $probdesc 问题描述
 * @property int $c_time 添加时间
 * @property int $u_time 更新时间
 * @property string $createby 创建人
 */
class ProblemType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%problem_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['probdesc','required','on'=>'add'],
            [['c_time', 'u_time'], 'integer'],
            [['probdesc'], 'string', 'max' => 200],
            [['createby'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'probdesc' => '问题描述',
            'c_time' => '添加时间',
            'u_time' => '更新时间',
            'createby' => '创建人',
        ];
    }

    public function create($data)
    {
        $this->scenario = 'add';
        if($this->load($data) && $this->validate()){
            $admin = Admin::findIdentity(yii::$app->user->getId());
            $model = new ProblemType();
            $model->probdesc = $this->probdesc;
            $model->c_time = time();
            $model->createby = $admin->username;
            if($model->save(false)){
                return true;
            }
        }
        return false;
    }

    /*
     *获取分类枚举值
     */
    public static function dropDownList()
    {
        $query = static::find();
        $enums = $query->all();
        return $enums? ArrayHelper::map($enums,'probdesc','probdesc'):[];
    }
}
