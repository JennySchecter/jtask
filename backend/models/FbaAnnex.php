<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ts_fba_annex".
 *
 * @property int $id
 * @property int $fbaId
 * @property string $filePath 附件上传文件名
 * @property string $fileName 显示文件名
 */
class FbaAnnex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ts_fba_annex';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fbaId'], 'integer'],
            [['filePath'], 'string', 'max' => 64],
            [['fileName'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fbaId' => 'Fba ID',
            'filePath' => '附件上传文件名',
            'fileName' => '显示文件名',
        ];
    }
}
