<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 2018/9/19
 * Time: 22:50
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        // 添加 "/admin/index" 权限
        $adminIndex = $auth->createPermission('/admin/index');
        $adminIndex->description = '后台用户列表';
        $auth->add($adminIndex);
        // 创建一个角色 '后台用户管理'，并为该角色分配"/admin/index"权限
        $adminManager = $auth->createRole('客服管理');
        $auth->add($adminManager);
        $auth->addChild($adminManager,$adminIndex);
        // 为用户 jenny（该用户的id=195） 分配角色 "客服管理" 权限
        $auth->assign($adminManager, 195); // 195是jenny用户的id
    }

    public function actionInit2()
    {
        $auth = Yii::$app->authManager;
        $adminCreate = $auth->createPermission('/admin/create');
        $auth->add($adminCreate);
        $adminUpdate = $auth->createPermission('/admin/update');
        $auth->add($adminUpdate);
        $adminDelete = $auth->createPermission('/admin/delete');
        $auth->add($adminDelete);

        $adminManager = $auth->getRole('客服管理');
        $auth->addChild($adminManager,$adminCreate);
        $auth->addChild($adminManager,$adminUpdate);
        $auth->addChild($adminManager,$adminDelete);

    }
}