<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ts_channel".
 *
 * @property int $id
 * @property string $name 渠道名称
 * @property string $code 简码
 * @property string $url 渠道官方网址
 * @property int $type 计费方式：1-A类；2-B类；3-C类
 * @property string $remark 备注
 * @property string $relation 关联接口
 * @property string $tel 电话
 * @property int $status 状态：0-正常；1-禁用
 * @property int $daysOut 单号过期天数
 * @property double $declareUp 申报价值上限
 * @property double $declareLow 申报价值下限
 * @property string $wordBlack 渠道禁用词
 * @property string $wordWhite 白名单
 * @property double $volumeWeight 体积重被除数
 * @property int $parentId
 * @property int $storageId
 * @property int $sort
 * @property string $line 快慢线
 */
class Channel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_channel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'daysOut', 'parentId', 'storageId', 'sort'], 'integer'],
            [['declareUp', 'declareLow', 'volumeWeight'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 2],
            [['url', 'wordBlack', 'wordWhite'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 500],
            [['relation'], 'string', 'max' => 15],
            [['tel'], 'string', 'max' => 20],
            [['line'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'url' => 'Url',
            'type' => 'Type',
            'remark' => 'Remark',
            'relation' => 'Relation',
            'tel' => 'Tel',
            'status' => 'Status',
            'daysOut' => 'Days Out',
            'declareUp' => 'Declare Up',
            'declareLow' => 'Declare Low',
            'wordBlack' => 'Word Black',
            'wordWhite' => 'Word White',
            'volumeWeight' => 'Volume Weight',
            'parentId' => 'Parent ID',
            'storageId' => 'Storage ID',
            'sort' => 'Sort',
            'line' => 'Line',
        ];
    }

    public static function dropDrownList()
    {
        $query = static::find();
        $enums = $query->all();
        return $enums? ArrayHelper::map($enums,'id','name'):[];
    }

    public static function dropDrownParent()
    {
        $query = static::find();
        $enums = $query->where(['parentId'=>0])->all();
        return $enums? ArrayHelper::map($enums,'id','name'):[];
    }

    public static function dropDrownChild($pid)
    {
        $query = static::find();
        $enums = $query->where(['parentId'=>$pid])->all();
        return $enums? ArrayHelper::map($enums,'id','name'):[];
    }
}
