<?php

use admin\widgets\Alert;

$user = Yii::$app->user;
if ($user->isGuest) {
    return Yii::$app->response->redirect(['auth/login']);
}

$this->beginContent('@app/views/layouts/base.php');

$title = isset(Yii::$app->controller->title) ? Yii::$app->controller->title : Yii::$app->params["title"];

if (isset(Yii::$app->params["tmpSubMenuId"])) {
    $this->registerJs("$('#" . Yii::$app->params["tmpSubMenuId"] . "').collapse()");
}

$brand = isset(Yii::$app->controller->brand) ? Yii::$app->controller->brand : null;
?>
<div class="container-fluid">
    <!-- Main Sidebar Menu -->
    <div id="menu" class="hidden-print hidden-xs">
        <div id="sidebar-fusion-wrapper">
            <div id="brandWrapper" class="center" style="height: 80px; white-space: nowrap;">
                <a href="#"><span class="text"><?= $title; ?></span></a>
                <span class="brandName">
                    <?= Yii::$app->user->identity->name; ?>
                </span>
            </div>
            <ul class="menu list-unstyled" id="navigation_current_page">
                <?php if ($user->can('member.*')): ?>
                    <li class="">
                        <a href="<?=
                        Yii::$app->tool->toBaseUrl([
                            "member/index"]);

                        ?>">
                            <span>會員管理</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($user->can('announce.*')): ?>
                    <li class="">
                        <a href="<?=
                        Yii::$app->tool->toBaseUrl([
                            "announce/index"]);

                        ?>">
                            <span>資訊發佈</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($user->can('admin.*')): ?>
                    <li class="hasSubmenu">
                        <a href="#admin" data-toggle="collapse" class="" aria-expanded="false">
                            <span>後台帳號管理</span>
                        </a>
                        <ul class="collapse" id="admin" aria-expanded="false">
                            <li class="">
                                <a href="<?=
                                Yii::$app->tool->toBaseUrl([
                                    "admin/index"]);

                                ?>">
                                    <span>管理員列表</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=
                                Yii::$app->tool->toBaseUrl([
                                    "admin/role"]);

                                ?>">
                                    <span>角色管理</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=
                                Yii::$app->tool->toBaseUrl([
                                    "permission/index"]);

                                ?>">
                                    <span>權限管理</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <!-- Content -->
    <div id="content">
        <div class="navbar hidden-print box main navbar-inverse" role="navigation">
            <div class="user-action user-action-btn-navbar pull-left border-right">
                <button class="btn btn-sm btn-navbar btn-primary btn-stroke">
                    <i class="fa fa-bars fa-2x"></i>
                </button>
            </div>
            <div class="user-action pull-right menu-right-hidden-xs menu-left-hidden-xs">
                <div class="dropdown username pull-left">
                    <a class="dropdown-toggle " data-toggle="dropdown" href="#">
                        <span class="media margin-none">
                            <span class="">
                                <?= Yii::$app->user->identity->name; ?>
                                <span class="caret"></span>
                            </span>
                        </span>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="<?= Yii::$app->tool->toBaseUrl(["/auth/logout"]); ?>">登出</a></li>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- // END navbar -->
        <div class="layout-app">
            <!-- row -->
            <div class="row row-app margin-none">
                <!-- col -->
                <div class="col-md-12">
                    <!-- col-separator -->
                    <div class="col-separator col-separator-first border-none">
                        <!-- col-table -->
                        <div class="innerAll">
                            <?= Alert::widget() ?>
                            <?= $content; ?>
                        </div>
                        <!-- // END col-table -->
                    </div>
                    <!-- // END col-separator -->
                </div>
                <!-- // END col -->
            </div>
            <!-- // END row-app -->
        </div>
    </div>
    <!-- // Content END -->
    <div class="clearfix"></div>
    <!-- // Sidebar menu & content wrapper END -->
</div>
<div id="modalSpace"></div>
<?php $this->endContent(); ?>

