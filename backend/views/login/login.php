<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 2018/9/17
 * Time: 0:28
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html class="login-bg">
<head>
    <title>急速系统 - 后台管理</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- bootstrap -->
    <link href="/statics/css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="/statics/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="/statics/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="/statics/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/statics/css/elements.css" />
    <link rel="stylesheet" type="text/css" href="/statics/css/icons.css" />

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="/statics/css/lib/font-awesome.css" />

    <!-- this page specific styles -->
    <link rel="stylesheet" href="/statics/css/compiled/signin.css" type="text/css" media="screen" />

    <!-- open sans font -->
    <link href='http://fonts.useso.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css' />

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>


<div class="row-fluid login-wrapper">
    <a class="brand" href="index.html"></a>

    <div class="span4 box">
        <div class="content-wrap">
            <h6>急速系统 - 后台管理</h6>
            <?php
                $form = ActiveForm::begin([
                        'fieldConfig' => [
                                'template' => '{error}{input}',
                        ],
                ])
            ?>
            <?=$form->field($model,'username')->textInput(['class'=>'span12','placeholder'=>'账号'])?>
            <?=$form->field($model,'password')->passwordInput(['class'=>'span12','placeholder'=>'密码'])?>
            <a href="" class="forgot">忘记密码?</a>
            <?=$form->field($model,'rememberMe')->checkbox([
                    'id' => 'remember-me',
                    'template' => '<div class="remember">{input}<label for="remember-me">记住我</label>'
            ])?>
            <?=Html::submitButton('登录',['class'=>'btn-glow primary index'])?>
            <?php $form = ActiveForm::end()?>
            <!--<input class="span12" type="text" placeholder="管理员账号" />
            <input class="span12" type="password" placeholder="管理员密码" />
            <a href="#" class="forgot">忘记密码?</a>
            <div class="remember">
                <input id="remember-me" type="checkbox" />
                <label for="remember-me">记住我</label>
            </div>
            <a class="btn-glow primary login" href="index.html">登录</a>-->
        </div>
    </div>

    <!--<div class="span4 no-account">
        <p>没有账户?</p>
        <a href="signup.html">注册</a>
    </div>-->
</div>

<!-- scripts -->
<script src="/statics/js/jquery-latest.js"></script>
<script src="/statics/js/bootstrap.min.js"></script>
<script src="/statics/js/theme.js"></script>

<!-- pre load bg imgs -->
<script type="text/javascript">
    $(function () {
        // bg switcher
        var $btns = $(".bg-switch .bg");
        $btns.click(function (e) {
            e.preventDefault();
            $btns.removeClass("active");
            $(this).addClass("active");
            var bg = $(this).data("img");

            $("html").css("background-image", "url('/images/bgs/" + bg + "')");
        });

    });
</script>

</body>
</html>
