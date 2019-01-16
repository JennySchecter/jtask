<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_abnormal_notify".
 *
 * @property int $id
 * @property string $express_num 转单号
 * @property int $user_id 用户id
 * @property int $count 该异常件已经通知的次数
 */
class AbnormalNotify extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_abnormal_notify';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'count'], 'integer'],
            [['express_num'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'express_num' => 'Express Num',
            'user_id' => 'User ID',
            'count' => 'Count',
        ];
    }
}
