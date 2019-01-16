<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%pickup}}".
 *
 * @property int $id
 * @property int $userid 会员ID
 * @property string $username 会员账号
 * @property string $address 取件地址
 * @property int $s_time 开始时间
 * @property int $e_time 结束时间
 * @property int $nums 件数
 * @property double $weight
 * @property int $last_time 最迟取件时间
 * @property int $status 0-待取件1-已取件2-已超时
 * @property int $c_time 创建时间
 */
class Pickup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pickup}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'username', 'address', 's_time', 'e_time', 'nums', 'weight', 'last_time'], 'required'],
            [['userid', 's_time', 'e_time', 'nums', 'last_time', 'status', 'c_time'], 'integer'],
            [['weight'], 'number'],
            [['username', 'address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '会员ID',
            'username' => '会员账号',
            'address' => '取件地址',
            's_time' => '开始时间',
            'e_time' => '结束时间',
            'nums' => '件数',
            'weight' => '重量',
            'last_time' => '最迟取件时间',
            'status' => '状态',
            'c_time' => '创建时间',
        ];
    }
}
