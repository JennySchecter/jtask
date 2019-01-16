<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%problem_log}}".
 *
 * @property int $id
 * @property int $waybillId 问题件运单ID
 * @property int $actionerId 操作人ID
 * @property string $actioner_name 操作人账号
 * @property int $datetime 操作时间
 * @property string $detail 操作详情
 */
class ProblemLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%problem_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['waybillId', 'actionerId', 'actioner_name', 'datetime', 'detail'], 'required'],
            [['waybillId', 'actionerId', 'datetime'], 'integer'],
            [['actioner_name'], 'string', 'max' => 60],
            [['detail'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'waybillId' => '问题件运单ID',
            'actionerId' => '操作人ID',
            'actioner_name' => '操作人账号',
            'datetime' => '操作时间',
            'detail' => '操作详情',
        ];
    }

    public static function addLog($data)
    {
        $admin = Admin::findIdentity(Yii::$app->user->getId());
        $model = new ProblemLog();
        $model->waybillId = $data['waybillId'];
        $model->actionerId = $admin['id'];
        $model->actioner_name = $admin['username'];
        $model->datetime = time();
        $model->detail = $data['detail'];
        if($model->save()){
            return true;
        }
        return false;
    }
}
