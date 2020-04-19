<?php

use admin\widgets\Alert;

$this->beginContent('@app/views/layouts/base.php');
$this->registerCss("body { background-color: transparent; }");
$this->registerJs('$(parent.document).find("iframe.iframe-modal").css("height", 1500);');
$this->registerJs("stopLoading();");

if (isset(Yii::$app->params["modalWidth"])) {
    $this->registerCss("@media screen and (min-width: 768px){.modal-dialog { width: " . Yii::$app->params["modalWidth"] . "; }}");
}

?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title section-title">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close" onclick="closeModal()">
                    x
                </button>
                <h4 class="sub title"><?= Yii::$app->controller->title; ?></h4>
            </div>
        </div>
        <?php
        if (!isset(Yii::$app->params["hideAlert"])) {
            echo Alert::widget();
        }

        ?>
        <div class="innerAll">
            <?= $content; ?>
        </div>
    </div>
</div>

<?php $this->endContent(); ?>
