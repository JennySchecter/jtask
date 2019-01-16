<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ts_storage".
 *
 * @property int $id
 * @property string $name 仓库名
 * @property string $contact 收件人姓名
 * @property string $state 省州
 * @property string $city 城市
 * @property string $address 详细地址
 * @property string $tel 电话
 * @property string $zip 邮编
 * @property string $remark 注备
 */
class Storage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_storage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'contact', 'state', 'city', 'address', 'tel', 'zip'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['contact', 'state', 'city', 'tel'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 300],
            [['zip'], 'string', 'max' => 10],
            [['remark'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '仓库名',
            'contact' => '收件人姓名',
            'state' => '省州',
            'city' => '城市',
            'address' => '详细地址',
            'tel' => '电话',
            'zip' => '编邮',
            'remark' => '注备',
        ];
    }

    public static function dropDrownList()
    {
        $query = static::find();
        $enums = $query->all();
        return $enums ? ArrayHelper::map($enums,'id','name'):[];
    }
}
