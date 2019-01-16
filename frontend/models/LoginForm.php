<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\User;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'账号',
            'password'=>'密码',
            'rememberMe'=>'记住我',
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *自定义密码验证方法
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        // hasErrors方法，用于获取rule失败的数据
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码不正确.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            $lifetime = $this->rememberMe ? time()+3600*24:0;
            session_set_cookie_params($lifetime);
            \yii::$app->session['user'] = [
                'username' => $this->username,
                'isLogin' => true,
            ];
            return true;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            // 根据用户名 调用认证类 User 的 findByUsername 获取用户认证信息
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
