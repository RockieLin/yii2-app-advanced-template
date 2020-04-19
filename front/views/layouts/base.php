<?php

use front\assets\AppAsset;
use yii\helpers\Html;

$asset = AppAsset::register($this);
$this->beginPage()

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= Yii::$app->controller->title; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <?php $this->head() ?>
        <?= Html::csrfMetaTags() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?= $content; ?>
        <?= \front\widgets\Alert::widget() ?>
        <?php $this->endBody() ?>
    </body>

</html>
<?php $this->endPage() ?>
