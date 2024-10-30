<?php
$this->beginContent('@app/views/layouts/base.php');

?>
<style>
    body{
        background: transparent;
    }
    #loading-overlay {
        position: fixed;
        background-color: transparent;
        width: 100vw;
        height: 100vh;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        z-index: 1030;
        color: #333;
        text-align: center;
        padding-top: 45vh;
        font-size: 2vh;
        overflow-y: hidden;
        opacity: 0.8;
    }
</style>
<div id="loading-overlay">
    <span id="message">Loading</span>
    <br>
    <img style="width: 50px; margin-top: 20px;" src="<?= Yii::$app->params['staticFileUrl']; ?>/images/loading.svg">
</div>
<?= $content; ?>
<?php $this->endContent(); ?>
