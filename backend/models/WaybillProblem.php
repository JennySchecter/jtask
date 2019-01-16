<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%waybill_problem}}".
 *
 * @property int $id
 * @property int $waybillId 运单ID
 * @property int $deal_status 问题运单的状态0-未处理1-处理中2-处理完成
 * @property int $c_time 设为问题件时间
 * @property int $up_time 更新时间
 * @property string $remark 设为问题件原因
 * @property string $create_user 创建者
 * @property int $create_id 创建人id
 */
class WaybillProblem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%waybill_problem}}';
    }

    /**
     * 关联waybill表
     */
    public function getWaybill()
    {
        return $this->hasOne(Waybill::className(),['id'=>'waybillId']);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['waybillId', 'remark'], 'required'],
            [['waybillId', 'deal_status', 'c_time', 'up_time', 'create_id'], 'integer'],
            [['remark'], 'string', 'max' => 200],
            [['create_user'], 'string', 'max' => 60],
            ['dealcontent','string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'waybillId' => '运单ID',
            'deal_status' => '状态',
            'c_time' => '设为问题件时间',
            'up_time' => '更新时间',
            'remark' => '设为问题件原因',
            'create_user' => '创建者',
            'create_id' => '创建人id',
            'dealcontent' => '处理意见'
        ];
    }

    public function create($data)
    {
        $admin = Admin::findIdentity(Yii::$app->user->getId());
        if($this->load($data) & $this->validate()){
            $model = new WaybillProblem();
            $model->waybillId = $this->waybillId;
            $model->c_time = time();
            $model->remark = $this->remark;
            $model->create_id = $admin['id'];
            $model->create_user = $admin['username'];

            if($model->save(false)){
                return true;
            }
        }
        return false;
    }

    public function deal($data,$id)
    {
        $model = WaybillProblem::find()->where(['id'=>$id])->one();
        if($this->load($data)){
            $model->dealcontent = $this->dealcontent;
            $model->deal_status = 1;
            $model->up_time = time();
            if($model->save(false)){
                return true;
            }
        }
        return false;
    }
}
