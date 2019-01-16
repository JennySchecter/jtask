<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 2018/9/19
 * Time: 15:54
 */

namespace backend\models;

use yii\base\Model;
use backend\models\Admin;

/**
 * Class CreateForm
 * @package backend\models
 */
class CreateForm extends Model
{
    public $username;
    public $password;
    public $passwordC;
    public $datetime;

    /**
     * @inheritdoc
     * 对数据的校验规则
     */
    public function rules()
    {
        return [
            // 对username的值进行两边去空格过滤
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required', 'message' => '账号不能为空'],
            // unique表示唯一性，targetClass表示的数据模型 这里就是说Admin模型对应的数据表字段username必须唯一
            ['username', 'unique', 'targetClass' => '\backend\models\Admin', 'message' => '账号已存在.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['password', 'required', 'message' => '密码不可以为空'],
            ['password', 'string', 'min' => 6, 'tooShort' => '密码至少填写6位'],
            ['passwordC','compare','compareAttribute'=>'password','message'=>'两次密码必须一致'],
            // default 默认在没有数据的时候才会进行赋值
            ['datetime', 'default', 'value' => time()],
        ];
    }

    /**
     * Signs user up.
     *
     * @return true|false 添加成功或者添加失败
     */
    public function create()
    {
        if (!$this->validate()) {
            return null;
        }

        // 实现数据入库操作
        $admin = new Admin();
        $admin->username = $this->username;
        $admin->datetime = $this->datetime;
        // 设置密码
        $admin->setPassword($this->password);
        // 生成 "remember me" 认证key
        $admin->generateAuthKey();
        return $admin->save(false);
    }
}