<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_audit_log".
 *
 * @property int $id
 * @property int $sid 异常件调查工单id
 * @property int $operator_id 操作人id
 * @property string $operator_name 操作人姓名（冗余字段）
 * @property string $desc 操作描述
 * @property int $flag 0不通过1通过
 * @property int $time 时间
 */
class AuditLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_audit_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sid', 'operator_id', 'operator_name', 'desc', 'time'], 'required'],
            [['sid', 'operator_id', 'flag', 'time'], 'integer'],
            [['operator_name'], 'string', 'max' => 20],
            [['desc'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => '异常件调查工单id',
            'operator_id' => '操作人id',
            'operator_name' => '操作人姓名（冗余字段）',
            'desc' => '操作描述',
            'flag' => '0不通过1通过',
            'time' => '时间',
        ];
    }

    public static function addLog($data)
    {
        $model = new AuditLog();
        $model->sid = $data['sid'];
        $model->flag = $data['flag'];
        $model->operator_id = $data['operator_id'];
        $model->operator_name = $data['operator_name'];
        $model->desc = $data['desc'];
        $model->time = $data['time'];
        $res = $model->save();
        if($res){
            return true;
        }
        return false;
    }
}
