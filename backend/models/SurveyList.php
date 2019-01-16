<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%survey_list}}".
 *
 * @property int $id
 * @property int $it_id 调查类型ID
 * @property int $dc_num 调查编号
 * @property int $order_num 订单编号
 * @property string $member_name 客户名称
 * @property int $dc_channel 调查渠道
 * @property string $description 情况描述
 * @property string $create_user 创建人
 * @property int $c_time 创建日期
 * @property int $isadd 是否追加
 * @property string $feedback 反馈描述
 * @property string $deal_user 处理人
 * @property int $deal_time 处理日期
 * @property int $next_time 下次联系日期
 * @property int $deal_num 处理次数
 * @property int $status 状态0-新建工单1-处理中2-预归档3-已归档
 * @property int $dc_result 调查结果1-赔偿，2-致歉
 * @property int $undertake 承担方1-官方2-公司
 * @property string $undertake_name 承担方姓名/公司内部人员姓名
 * @property double $compensate_money 赔偿金额
 * @property string $file_user 归档人
 * @property int $file_time 归档日期
 * @property int $audit 审核
 * @property int $write_off 核销
 */
class SurveyList extends \yii\db\ActiveRecord
{
    public $appendContent;
    public $dealContent;
    public $repay_time;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%survey_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['it_id','order_num','description','dc_channel','create_user'], 'required'],
            [['it_id', 'c_time', 'isadd', 'deal_time', 'deal_num','next_time', 'status', 'dc_result','pc_result', 'file_time','audit','write_off','channelParentId','channelChildId'], 'integer'],
            [['description', 'feedback','dc_num','file_content'], 'string'],
            [['company_money','office_money'], 'number'],
            [['member_name', 'create_user', 'deal_user','office_name','departmentIds','staffIds','file_user'], 'string', 'max' => 200],
            ['order_num','checkorder','on'=>'add'],
            //['feedback','required','on'=>'dealwith'],
            ['repay_time','infivedays','on'=>'dealwith'],
            [['dc_result','undertake','undertake_name','compensate_money'],'required','on'=>'beforefile'],
            ['appendContent','required','on'=>'append'],
            ['dealContent','required','on'=>'dealwith']
        ];
    }

    /*
     * 创建异常件工单必须有订单编号,一个订单号在被归档后可重新添加为异常件
     */
    public function checkorder()
    {
        if(!$this->hasErrors()){
            $order = Waybill::find()->where(['orderNum'=>$this->order_num])->one();
            $fbaOrder = Fba::find()->where(['orderNum'=>$this->order_num])->one();
            if(is_null($order) && is_null($fbaOrder)){
                $this->addError('order_num','该订单号不存在');
            }
            $survey = SurveyList::find()->where('order_num=:orderNum and status !=3',['orderNum'=>$this->order_num])->one();

            if(!empty($survey)){
                $this->addError('order_num','该订单已被提取为异常件');
            }
        }
    }
    
    //下次回访日期必须是当前时间的五天内
    public function infivedays()
    {
        if(!$this->hasErrors()){
            if(($this->next_time <time()) || ($this->next_time-time()) > 5*86400 ){
                $this->addError('next_time','请您在5天内联系客户！');
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'it_id' => '调查类型ID',
            'dc_num' => '调查编号',
            'order_num' => '订单编号',
            'member_name' => '客户名称',
            'dc_channel' => '调查渠道名称',
            'channelParentId' => '父渠道',
            'channelChildId' => '子渠道',
            'description' => '情况描述',
            'create_user' => '创建人',
            'c_time' => '创建日期',
            'isadd' => '是否追加',
            'feedback' => '反馈描述',
            'deal_user' => '处理人',
            'deal_time' => '处理日期',
            'next_time' => '下次联系日期',
            'deal_num' => '处理次数',
            'status' => '状态',
            'dc_result' => '调查结果',
            'pc_result' => '赔偿结果',
            'office_money' => '官方承担金额',
            'office_name' => '官方名称',
            'company_money' => '公司承担金额',
            'departmentIds' => '承担部门',
            'staffIds' => '承担员工',
            'file_content' => '归档意见',
            'file_user' => '归档人',
            'file_time' => '归档日期',
            'appendContent'=> '情况描述追加',
            'dealContent'=> '处理反馈',
            'audit'=> '审核',
            'write_off'=> '核销',
        ];
    }

    public function create($data)
    {
        $this->scenario = 'add';
        if($this->load($data) && $this->validate()){
            $model = new SurveyList();
            $model->it_id = $this->it_id;
            $model->dc_num = $this->dc_num;
            $model->order_num = $this->order_num;
            $model->member_name = $this->member_name;
            $model->dc_channel = $this->dc_channel;
            $model->description = $this->description;
            $model->create_user = $this->create_user;
            $model->c_time = time();

            $waybill = Waybill::find()->where(['orderNum'=>$this->order_num])->one();
            $model->channelParentId = $waybill['channelParentId'];
            $model->channelChildId = $waybill['channelChildId'];
            if($model->save(false)){
                return true;
            }

        }
        return false;
    }

    //处理工单
    public function dealwith($data,$id)
    {
        $this->scenario = 'dealwith';
        //var_dump($data);die;
        if($this->load($data) && $this->validate()){
            $admin = Admin::findIdentity(Yii::$app->user->getId());
            $model = SurveyList::find()->where(['id'=>$id])->one();
            if($model->deal_num == 0){
                $model->feedback = $this->dealContent;
            }else{
                $model->feedback = $model->feedback .';' . date('Y-m-d') . '处理反馈：' . $this->dealContent . '-处理人：' . Yii::$app->user->getIdentity()->username;
            }

            $model->deal_user = $admin['username'];
            $model->deal_time = time();
            $model->deal_num += 1;
            $model->status = 1;
            $model->isadd = 0;//处理后追加状态变为0
            $model->next_time = $this->next_time;
            if($model->save(false)){
                return true;
            }
        }
        return false;
    }

    //处理结果归档
    public function compensate($post)
    {
        $model = SurveyList::find()->where(['id'=>$post['s_id']])->one();
        if($model){
            $model->status = 2;
            $model->file_content = $post['file_content'];
            $model->file_user = Yii::$app->user->getIdentity()->username;
            $model->file_time = time();
            //调查结果为致歉
            if($post['dc_result'] == 1){
                $model->dc_result = 1;
            }
            //调查结果为赔偿
            if($post['dc_result'] == 2){
                $model->dc_result = 2;
                //仅为官方赔偿
                if(count($post['pc_type']) == 1 && in_array(1,$post['pc_type'])){
                    $model->pc_result = 1;
                    $model->office_name = $post['office_name'];
                    $model->office_money = $post['office_money'];
                }
                //仅为公司赔偿
                if(count($post['pc_type']) == 1 && in_array(2,$post['pc_type'])){
                    $model->pc_result = 2;
                    $model->departmentIds = implode(';',$post['departments']);
                    $model->staffIds = implode(';',$post['company_name']);
                    $model->company_money = $post['company_money'];
                }
                //公司与官方均赔偿
                if(count($post['pc_type']) == 2){
                    $model->pc_result = 3;
                    $model->office_name = $post['office_name'];
                    $model->office_money = $post['office_money'];
                    $model->departmentIds = implode(';',$post['departments']);
                    $model->staffIds = implode(';',$post['company_name']);
                    $model->company_money = $post['company_money'];
                }
            }

            $model->save(false);
        }
        return false;
    }

    //追加情况描述
    public function append($data,$id)
    {
        $this->scenario = 'append';
        if($this->load($data) && $this->validate()){
            $model = SurveyList::find()->where(['id'=>$id])->one();
            $model->description = $model->description.';'.date('Y-m-d').'追加：'.$this->appendContent.'-追加人：'.Yii::$app->user->getIdentity()->username;
            $model->isadd = 1;
            if($model->save(false)){
                return true;
            }
        }
        return false;
    }
}
