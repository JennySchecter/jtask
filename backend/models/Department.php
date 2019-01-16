<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_department".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $description 部门介绍
 * @property int $level 部门层级
 * @property int $parent 上级部门ID
 * @property string $path 部门层级关系
 * @property int $sort 排序序号
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'parent', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 100],
            [['path'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'description' => '部门介绍',
            'level' => '部门层级',
            'parent' => '上级部门ID',
            'path' => '部门层级关系',
            'sort' => '排序序号',
        ];
    }


}
