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

        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-hand-lizard-o"></i> <span>我要取件</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/pickup/create'])?>"><i class="fa fa-circle-o"></i> 我要取件</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/pickup/index'])?>"><i class="fa fa-circle-o"></i> 取件记录</a></li>
                </ul>
            </li>
        </ul>

        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <a href="#">
                    <i class="fa  fa-send"></i> <span>我要寄件</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?=yii\helpers\Url::to(['/user-send/create'])?>"><i class="fa fa-circle-o"></i> 我要寄件</a></li>
                    <li><a href="<?=yii\helpers\Url::to(['/user-send/index'])?>"><i class="fa fa-circle-o"></i> 寄件记录</a></li>
                </ul>
            </li>
        </ul>
    </section>

</aside>
