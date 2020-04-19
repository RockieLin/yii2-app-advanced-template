<?php

use yii\bootstrap\BootstrapAsset;
use yii\helpers\Html;

$asset = BootstrapAsset::register($this);
$this->beginPage()

?>
<!DOCTYPE html>
<html class="paceSimple app sidebar sidebar-fusion sidebar-kis footer-sticky navbar-sticky">

    <head>
        <title>API</title>
        <?php $this->head() ?>

        <meta charset="utf-8">
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?= $content; ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>