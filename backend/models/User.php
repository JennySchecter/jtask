<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username 登录名
 * @property string $auth_key Auth Key
 * @property string $password_hash 密码
 * @property string $password_reset_token Token
 * @property string $email 邮箱
 * @property int $role 角色
 * @property int $created_at 创建日期
 * @property int $updated_at 更新日期
 * @property string $name 客户名称
 * @property string $code 简码
 * @property string $mobile 电话
 * @property string $wechat 微信号
 * @property string $qq QQ号
 * @property string $address 地址
 * @property string $address2 地址2
 * @property string $address3 地址3
 * @property string $address4 地址4
 * @property string $address5 地址5
 * @property string $paperworkCode 证件号码
 * @property string $paperworkCode2 证件号码2
 * @property double $balance 账户余额
 * @property double $balance1 大货小包限额
 * @property double $balance2 小包限额
 * @property double $balance3 其他限额
 * @property double $creditMoney 信用额度
 * @property string $goodsName 默认申报品名中文
 * @property string $goodsEnglish 默认品名英文
 * @property string $goodsCode 默认品名海关代码
 * @property double $goodsPrice 默认品名单价
 * @property string $special 特殊要求
 * @property string $contract 合同内容
 * @property int $payType 付款方式：1-现付；2-周结；3-半月结；4-月结
 * @property int $authSimple 傻瓜创建运单：0-可以；1-不可以
 * @property int $authReturnApiError 返回官方错误：0-不反回；1-返回
 * @property string $apiKey API KEY
 * @property string $esyUser E商赢用户
 * @property string $esyPass E商赢密码
 * @property string $token TOKEN
 * @property int $storageId 所属仓库
 * @property int $department 所属站点
 * @property int $status 状态：0-正常；1-禁用
 * @property int $groupid 所属客户组别id
 * @property int $isvip 是否为vip客户0-否 1-是
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role', 'created_at', 'updated_at', 'payType', 'authSimple', 'authReturnApiError', 'storageId', 'department', 'status', 'groupid', 'isvip'], 'integer'],
            [['balance', 'balance1', 'balance2', 'balance3', 'creditMoney', 'goodsPrice'], 'number'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key', 'token'], 'string', 'max' => 32],
            [['name', 'paperworkCode', 'goodsName', 'goodsEnglish', 'esyUser', 'esyPass'], 'string', 'max' => 50],
            [['code', 'qq', 'goodsCode'], 'string', 'max' => 20],
            [['mobile'], 'string', 'max' => 15],
            [['wechat'], 'string', 'max' => 25],
            [['address', 'address2', 'address3', 'address4', 'address5'], 'string', 'max' => 150],
            [['paperworkCode2', 'apiKey'], 'string', 'max' => 64],
            [['special', 'contract'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '客户账号',
            'email' => 'Email',
            'name' => '客户名称',
            'code' => '客户简码',
            'mobile' => '手机',
            'wechat' => '微信',
            'qq' => 'qq',
            'address' => 'Address',
            'address2' => 'Address2',
            'address3' => 'Address3',
            'address4' => 'Address4',
            'address5' => 'Address5',
//            'paperworkCode' => 'Paperwork Code',
//            'paperworkCode2' => 'Paperwork Code2',
//            'balance' => 'Balance',
//            'balance1' => 'Balance1',
//            'balance2' => 'Balance2',
//            'balance3' => 'Balance3',
//            'creditMoney' => 'Credit Money',
//            'goodsName' => 'Goods Name',
//            'goodsEnglish' => 'Goods English',
//            'goodsCode' => 'Goods Code',
//            'goodsPrice' => 'Goods Price',
//            'special' => 'Special',
//            'contract' => 'Contract',
//            'payType' => 'Pay Type',
//            'authSimple' => 'Auth Simple',
//            'authReturnApiError' => 'Auth Return Api Error',
//            'apiKey' => 'Api Key',
//            'esyUser' => 'Esy User',
//            'esyPass' => 'Esy Pass',
//            'token' => 'Token',
//            'storageId' => 'Storage ID',
//            'department' => 'Department',
            'status' => '账号状态',
            'groupid' => '所属分组',
            'isvip' => '是否vip',
        ];
    }

    /*
     * 为用户批量设置分组
     */
    public function setGroup($ids = [],$gid)
    {
        if(User::updateAll(['groupid'=>$gid],['in','id',$ids])){
            return true;
        }
        return false;
    }
}
