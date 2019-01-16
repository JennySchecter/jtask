<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user_send}}".
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
 * @property string $memremark 客户备注
 * @property string $remark 公司备注
 */
class UserSend extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_send}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userid', 'username', 'company', 'expressNum', 'sendername', 'sendermobile', 'nums', 'weight', 'item', 'c_time'], 'required'],
            [['userid', 'nums', 'status', 'c_time', 'up_time', 'handleId'], 'integer'],
            [['weight'], 'number'],
            [['username', 'company', 'item'], 'string', 'max' => 200],
            [['expressNum', 'sendername'], 'string', 'max' => 50],
            [['sendermobile'], 'string', 'max' => 20],
            [['memremark', 'remark'], 'string', 'max' => 255],
            ['remark','required','on'=>'setprob']
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
            'status' => '状态',
            'c_time' => '创建时间',
            'up_time' => '更新时间',
            'handleId' => '处理人工号',
            'memremark' => '客户备注',
            'remark' => '公司备注',
        ];
    }
    //设置问题件发送站内信
    public function setProb($data,$id)
    {
        $this->scenario = 'setprob';
        if($this->load($data) && $this->validate()){

            //do 设为问题件 发送站内信
            $model = UserSend::find()->where(['id'=>$id])->one();
            $model->status = 1;
            $model->up_time = time();
            $model->handleId = yii::$app->user->getId();
            $model->remark = $this->remark;
            if($model->save(false)){
                return true;
            }
        }
        return false;
    }

    //客户寄件确认收货  留下处理人工号与处理时间
    public function receipt($id)
    {
        $model = UserSend::find()->where(['id'=>'$id'])->one();
        if($model){
            $model->handleId = Yii::$app->user->getId();
            $model->status = 2;
            $model->up_time = time();
            if($model->save(false)){
                return true;
            }
        }
        return false;
    }
}
