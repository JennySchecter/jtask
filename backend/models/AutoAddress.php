<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_auto_address".
 *
 * @property int $id
 * @property string $zip 邮编
 * @property int $countryId 国家ID
 * @property string $countryName 国家名称
 * @property string $countryCode 国家简写
 * @property string $province 省州
 * @property string $city 城市
 * @property string $address 详细地址
 */
class AutoAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_auto_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['countryId'], 'required'],
            [['countryId'], 'integer'],
            [['zip'], 'string', 'max' => 15],
            [['countryName'], 'string', 'max' => 20],
            [['countryCode'], 'string', 'max' => 5],
            [['province', 'city'], 'string', 'max' => 25],
            [['address'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'zip' => 'Zip',
            'countryId' => 'Country ID',
            'countryName' => 'Country Name',
            'countryCode' => 'Country Code',
            'province' => 'Province',
            'city' => 'City',
            'address' => 'Address',
        ];
    }
}
