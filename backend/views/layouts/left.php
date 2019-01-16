<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <!--<div class="user-panel">
            <div class="pull-left image">
                <img src="<?/*= $directoryAsset */?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>-->
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="">
                    <i class="fa  fa-support"></i> <span>权限管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <!--<li><a href="<?/*=yii\helpers\Url::to(['/adminrole/route'])*/?>"><i class="fa fa-circle-o"></i> 路由</a></li>
                    <li><a href="<?/*=yii\helpers\Url::to(['/adminrole/permission'])*/?>"><i class="fa fa-circle-o"></i> 权限</a></li>-->
                    <li><a href="<?=yii\helpers\Url::to(['/adminrole/role'])?>"><i class="fa fa-circle-o"></i> 角色</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/adminrole/assignment'])?>"><i class="fa fa-circle-o"></i> 分配</a></li>
                    <!--<li><a href="/adminrole/menu"><i class="fa fa-circle-o"></i> 菜单</a></li>-->
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="">
                    <i class="fa  fa-users"></i> <span>后台用户管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/admin/index'])?>"><i class="fa fa-circle-o"></i> 后台用户列表</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa  fa-object-ungroup"></i> <span>客户分组管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/group/index'])?>"><i class="fa fa-circle-o"></i> 分组列表</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa  fa-object-group"></i> <span>客服分组管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/kf-group/index'])?>"><i class="fa fa-circle-o"></i> 分组列表</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="glyphicon glyphicon-user"></i> <span>客户管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/user/index'])?>"><i class="fa fa-circle-o"></i> 客户列表</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/user-alarms/mail'])?>"><i class="fa fa-circle-o"></i> 发送站内信给客户</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-sticky-note-o"></i> <span>运单查询</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/waybill/index'])?>"><i class="fa fa-circle-o"></i> 运单列表</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/fba/index'])?>"><i class="fa fa-circle-o"></i> FBA列表</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/waybill/batch-search'])?>"><i class="fa fa-circle-o"></i> 批量查询</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/waybill/batch-update'])?>"><i class="fa fa-circle-o"></i> 批量修改</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-map"></i> <span>预录单管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/waybill/create-prerecord'])?>"><i class="fa fa-circle-o"></i> 创建预录单</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/waybill/prerecord-list'])?>"><i class="fa fa-circle-o"></i> 预录单列表</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-exclamation"></i> <span>问题件管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/waybill/problem-bill'])?>"><i class="fa fa-circle-o"></i> 问题件列表</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/problem-log/index'])?>"><i class="fa fa-circle-o"></i> 问题件日志</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-hourglass-2"></i> <span>正常件超时运单</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/waybill/overtime'])?>"><i class="fa fa-circle-o"></i>超时运单列表</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa  fa-exclamation-triangle"></i> <span>异常件调查</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/investigate-type/index'])?>"><i class="fa fa-circle-o"></i>异常件调查类型</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/survey-list/index'])?>"><i class="fa fa-circle-o"></i>异常件调查工单</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-hand-lizard-o"></i> <span>客户取件</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/pickup/index'])?>"><i class="fa fa-circle-o"></i> 取件记录</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa  fa-send"></i> <span>客户寄件</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/user-send/index'])?>"><i class="fa fa-circle-o"></i> 寄件记录</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-binoculars"></i> <span>运单追踪</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/trace/single'])?>"><i class="fa fa-circle-o"></i> 单票追踪</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/trace/batch'])?>"><i class="fa fa-circle-o"></i> 多票批量追踪</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-fw fa-book"></i> <span>知识库栏目</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/knowledge/index'])?>"><i class="fa fa-circle-o"></i> 文件列表</a></li>
                </ul>
            </li>
        </ul>
        <ul class="sidebar-menu">
            <li>
                <a href="<?=yii\helpers\Url::to(['/admin/setting'])?>">
                    <span class="glyphicon glyphicon-cog"></span>系统设置
                </a>
            </li>
        </ul>
        <!-- search form -->
        <!--<form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->
    </section>

</aside>
