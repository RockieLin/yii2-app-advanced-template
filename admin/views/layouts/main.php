<?php

use admin\widgets\Alert;

$user = Yii::$app->user;
if ($user->isGuest) {
    return Yii::$app->response->redirect(['auth/login']);
}

$this->beginContent('@app/views/layouts/base.php');

?>
<div class="container-fluid">
    <!-- Main Sidebar Menu -->
    <div id="menu" class="hidden-print hidden-xs">
        <div id="sidebar-fusion-wrapper">
            <div id="brandWrapper" class="center" style="height: 80px; white-space: nowrap;">
                <a href="#"><span id="menuTitle" class="text"><?= Yii::$app->params["title"]; ?></span></a>
                <span class="brandName">
                    <?= Yii::$app->user->identity->name; ?>
                </span>
            </div>
            <ul class="menu list-unstyled" id="navigation_current_page">
                <?= \admin\widgets\Menu::widget([
                    "menuData" => require(Yii::getAlias("@admin/config/menus.php"))
                ]) ?>
            </ul>
        </div>
    </div>
    <div id="content">
        <div class="navbar hidden-print box main navbar-inverse" role="navigation">
            <div class="user-action user-action-btn-navbar pull-left">
                <button class="btn btn-sm btn-navbar btn-primary btn-stroke">
                    <i class="fa fa-ellipsis-v fa-2x"></i>
                </button>
            </div>
            <ul class="notifications pull-right hidden-xs">
                <li class="dropdown notif">
                    <a href="#" class="dropdown-toggle dropdown-hover" data-toggle="dropdown" aria-expanded="false">
                        <i class="notif-block fa fa-user-o" style="margin: 8px 0;"></i>
                    </a>
                    <ul class="dropdown-menu chat media-list" style="left: -150px;top: 48px;">
<!--                        <li>-->
<!--                            <a href="#" class="auto-modal" data-href="/auth/me">個人資料</a>-->
<!--                        </li>-->
                        <li>
                            <a class="noframe" href="/auth/logout">登出</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="layout-app">
            <div class="row row-app margin-none">
                <div class="col-md-12">
                    <div class="col-separator col-separator-first border-none">
                        <div class="innerAll">
                            <?= Alert::widget() ?>
                            <?= $content; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div id="modalSpace"></div>
<?php $this->endContent(); ?>

