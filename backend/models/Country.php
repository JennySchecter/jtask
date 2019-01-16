<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ts_country".
 *
 * @property int $id
 * @property string $name 域区名称
 * @property string $english 英文名全称
 * @property string $code 简码
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['english'], 'string', 'max' => 30],
            [['code'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '域区名称',
            'english' => '英文名全称',
            'code' => '简码',
        ];
    }

    public static function dropDrownList()
    {
        $query = static::find();
        $enums = $query->all();
        return $enums ? ArrayHelper::map($enums,'id','name'):[];
    }
}
