<?php

common\assets\Theme\ModernBlueAsset::register($this);
$this->registerCss(".layout-app .col-separator {
  background-color: transparent;
}");

?>

<div class="row error" style="background: radial-gradient(ellipse,#FFF,#333);height: 100vh;padding-top: 18%;">
    <div class="col-md-4 col-md-offset-1 center">
        <div class="center">
            <img src="/images/error.png" class="error-icon" style="border: 0px;">
        </div>
    </div>
    <div class="col-md-5 content center" style="margin-top: 50px;">
        <h4 class="innerB">發生了一些錯誤</h4>
        <div class="well">
            <?= $exception->getMEssage() . "<br/>" . $exception->getFile() . " " . $exception->getLine(); ?>
        </div>
        <br/>
        <button class="btn btn-danger btn-lg" onclick="window.history.back();">回上頁</button>
    </div>
</div>