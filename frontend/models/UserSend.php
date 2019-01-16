<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "ts_user_send".
 *
 * @property int $id
 * @property int $userid 客户ID
 * @property string $username 客户账号
 * @property string $company 快递公司
 * @property string $expressNum 快递单号
 * @property string $sendername 寄件人姓名
 * @property string $sendermobile 寄件人手机号
 * @property int $nums 数量
 * @property double $weight 重量
 * @property string $item 物品
 * @property int $status 0-已提交，1-问题件，2-确认收货
 * @property int $c_time 创建时间
 * @property int $up_time 更新时间
 * @property int $handleId 处理人工号
 */
class UserSend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_user_send';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'username', 'company', 'expressNum', 'sendername', 'sendermobile', 'nums', 'weight', 'item'], 'required'],
            [['userid', 'nums', 'status', 'c_time', 'up_time', 'handleId'], 'integer'],
            //[['weight'], 'number'],
            [['username', 'company', 'item'], 'string', 'max' => 200],
            [['expressNum', 'sendername'], 'string', 'max' => 50],
            [['sendermobile'], 'string', 'max' => 20],
            ['sendermobile','checkmobile'],
            ['memremark','string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '客户ID',
            'username' => '客户账号',
            'company' => '快递公司',
            'expressNum' => '快递单号',
            'sendername' => '寄件人姓名',
            'sendermobile' => '寄件人手机号',
            'nums' => '数量',
            'weight' => '重量',
            'item' => '物品',
            'status' => '0-已提交，1-问题件，2-确认收货',
            'c_time' => '创建时间',
            'up_time' => '更新时间',
            'handleId' => '处理人工号',
            'memremark' => '客户备注',
            'remark' => '公司备注'
        ];
    }

    public function checkmobile()
    {
        if(!$this->hasErrors()){
            $reg = '/1[3|4|5|6|7|8][0-9]{9}/';
            if(!preg_match($reg,$this->sendermobile)){
                $this->addError('sendermobile','手机号码格式不正确');
            }
        }
    }

    public function create($data)
    {
        if($this->load($data) && $this->validate()){
            $model = new UserSend();
            $model->userid = $this->userid;
            $model->username = $this->username;
            $model->company = $this->company;
            $model->expressNum = $this->expressNum;
            $model->sendername = $this->sendername;
            $model->sendername = $this->sendername;
            $model->sendermobile = $this->sendermobile;
            $model->nums = $this->nums;
            $model->weight = $this->weight;
            $model->item = $this->item;
            $model->c_time = time();
            $model->memremark = $this->memremark;
            if($model->save(false)){
                return true;
            }
        }
        return false;
    }
}
