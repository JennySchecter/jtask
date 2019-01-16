<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_knowledge".
 *
 * @property int $id
 * @property string $subject 主题
 * @property string $attachment_path 附件地址
 * @property int $create_time
 * @property int $admin_id 创建者
 * @property string $admin_username 冗余字段
 */
class Knowledge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_knowledge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject', 'create_time', 'admin_id', 'admin_username'], 'required'],
            [['create_time', 'admin_id'], 'integer'],
            [['subject'], 'string', 'max' => 50],
            [['attachment_path'], 'string', 'max' => 200],
            [['admin_username'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => '主题',
            'attachment_path' => '附件地址',
            'create_time' => 'Create Time',
            'admin_id' => '创建者',
            'admin_username' => '冗余字段',
        ];
    }

    public function add($data)
    {
        $model = new Knowledge();
        $this->load($data);
        $model->subject = $this->subject;
        $model->attachment_path = $this->attachment_path;
        $model->admin_id = Yii::$app->user->getId();
        $model->admin_username = Yii::$app->user->getIdentity()->username;
        $model->create_time = time();

        if($model->save()){
            return true;
        }
        return false;
    }
}
