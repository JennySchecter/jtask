<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user_alarms}}".
 *
 * @property int $id 用户消息提醒
 * @property int $userId 用户ID
 * @property int $adminId 发送者ID
 * @property string $subject 主题
 * @property string $content 内容
 * @property int $datetime 日期
 * @property int $type   类型
 * @property string $expressNum   转单号
 * @property int $status 状态0-未读 1-已读
 */
class UserAlarms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_alarms}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId','adminId', 'subject', 'content'], 'required'],
            [['datetime', 'status','type'], 'integer'],
            [['subject','expressNum'], 'string', 'max' => 200],
            [['content'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户消息提醒',
            'userId' => '客户',
            'adminId' => '发送人',
            'type' => '类型',
            'expressNum' => '转单号',
            'subject' => '主题',
            'content' => '内容',
            'datetime' => '日期',
            'status' => '状态0-未读 1-已读',
        ];
    }

    public function create($data)
    {
        $model = new UserAlarms();
        if($this->load($data) && $this->validate()){
            $model->userId = $this->userId;
            $model->adminId = $this->adminId;
            $model->subject = $this->subject;
            $model->content = $this->content;
            $model->type = $this->type;
            $model->expressNum = $this->expressNum;
            $model->datetime = time();
            if($model->save(false)){
                return true;
            }
        }
        return false;
    }
}
