<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "ts_pickup".
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
        return 'ts_pickup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['userid', 'username', 'address', 's_time', 'e_time', 'nums', 'weight', 'last_time'], 'required','message'=>'不能为空'],
            //[['userid', 's_time', 'e_time', 'nums', 'last_time', 'status', 'c_time'], 'integer'],
            //[['weight'], 'number'],
            [['username', 'address'], 'string', 'max' => 255],
            ['e_time','checkrange'],
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
            's_time' => '取件时间段开始',
            'e_time' => '取件时间段结束',
            'nums' => '件数',
            'weight' => '重量',
            'last_time' => '最迟取件时间',
            'status' => '状态',
            'c_time' => '创建时间',
        ];
    }

    /**
     * 日期选择结束时间不能大于开始时间
     */
    public function checkrange()
    {
        if(!$this->hasErrors()){
            $starttime = strtotime($this->s_time);
            $endtime = strtotime($this->e_time);
            if($starttime > $endtime){
                $this->addError('e_time','结束时间不能小于开始时间');
            }
        }
    }

    public function create($data)
    {
        if($this->load($data) && $this->validate()){
            $model = new Pickup();
            $model->userid = $this->userid;
            $model->username = $this->username;
            $model->address = $this->address;
            $model->s_time = strtotime($this->s_time);
            $model->e_time = strtotime($this->e_time);
            $model->last_time = strtotime($this->last_time);
            $model->nums = $this->nums;
            $model->weight = $this->weight;
            $model->c_time = time();
            if($model->save(false)){
                return true;
            }
        }
        return false;
    }
}
