<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;
use yii\base\Security;


/**
 * This is the model class for table "{{%admin}}".
 *
 * @property int $id
 * @property string $username 用户名
 * @property string $auth_key
 * @property string $password_hash 密码
 * @property string $password_reset_token
 * @property string $nickName 昵称
 * @property int $datetime  创建时间
 * @property int $status 用户状态：1-正常；0-停用
 * @property int $groupid 所属客户组别id
 * @property int $kf_group 客服分组id
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;
    public $passwordC;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'nickName'], 'string', 'max' => 30],
            ['password','string'],
            ['passwordC','checkequal','on'=>['update','changePwd']],
            //['password','compare','compareAttribute'=>'passwordC','message'=>'两次密码不一致'],
            ['status','integer'],
            [['groupid','kf_group'],'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '账号',
            'nickName' => '昵称',
            'datetime' => '创建时间',
            'status' => '账号状态',
            'groupid' => '服务客户分组',
            'kf_group' => '客服分组',
            'password' => '密码',
            'passwordC' => '确认密码'
        ];
    }

    /**
 * @inheritdoc
 * 根据ts_admin表的主键（id）获取用户
 */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     * 根据access_token获取用户
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     * 用以标识 Yii::$app->user->id 的返回值
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     * 获取auth_key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     * 验证auth_key
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /*
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /*
     * 生成 "remember me" 认证key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * 根据username获取用户
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * 密码验证
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password,$this->password_hash);
    }

    public function checkequal()
    {
        if(!$this->hasErrors() && ($this->passwordC!='' || $this->password!='')){
            if($this->password != $this->passwordC){
                $this->addError('passwordC','两次密码不一致');
            }
        }
    }
    /**
     * 单个更新用户，昵称、账号状态、服务组别、客服分组
     */
    public function updateSingle($data,$id)
    {
        $this->scenario = 'update';
        $admin = Admin::findIdentity($id);
        if($this->load($data) && $this->validate()){
            $admin->nickName = $this->nickName;
            $admin->status = $this->status;
            $admin->groupid = $this->groupid;
            $admin->kf_group = $this->kf_group;

            if($this->passwordC != ''){
                $admin->password_hash = Yii::$app->security->generatePasswordHash($this->passwordC);
            }
            if($admin->save()){
                return true;
            }
        }
        return false;
    }

    public function changePwd($data,$id)
    {
        $this->scenario = 'changePwd';
        if($this->load($data) && $this->validate()){
            $model = Admin::findIdentity($id);
            $model->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            if($model->save()){
                return true;
            }
        }
        return false;
    }

    public function setGroup($ids = [],$gid)
    {
        if(Admin::updateAll(['groupid'=>$gid],['in','id',$ids])){
            return true;
        }
        return false;
    }
}
