<?php

use admin\assets\AppAsset;
use yii\helpers\Html;

$asset = AppAsset::register($this);
$staticUrl = Yii::$app->params["staticFileUrl"];
$title = isset(Yii::$app->controller->title) ? Yii::$app->controller->title : Yii::$app->params["title"];

$this->beginPage()

?>
<!DOCTYPE html>
<html class="paceSimple app sidebar sidebar-fusion sidebar-kis footer-sticky navbar-sticky">
    <head>
        <?= Html::csrfMetaTags() ?>
        <title><?= Yii::$app->params["environmentNotice"] . Html::encode($title) ?></title>
        <?php $this->head() ?>

        <meta charset="utf-8">
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="menu-right-hidden">
        <?php $this->beginBody() ?>
        <?= $content; ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
