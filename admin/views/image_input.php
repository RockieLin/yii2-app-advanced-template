<?php
$this->registerJs("imagePreview();");

if (!isset($size_type) || empty($size_type)) {
    $size_type = "common";
    $message = null;
} else {
    $message = "建議尺寸" . Yii::$app->params["thumbWidth"][$size_type]["width"] . " x " . Yii::$app->params["thumbWidth"][$size_type]["height"];
}

$ratio = Yii::$app->params["thumbWidth"][$size_type]["height"] / Yii::$app->params["thumbWidth"][$size_type]["width"];
if ($ratio == 1) {
    $padding = ($ratio / 2 * 100) . "%";
} else {
    $padding = ($ratio * 100) . "%";
}
?>
<div class="imageBlock center">
    <?php if (isset($show_label) && $show_label): ?>
        <label class="control-label"><?= $model->getAttributeLabel($name); ?></label><br/>
    <?php endif; ?>
    <a href="<?= $model->getImage($name); ?>" target="_blank" class="imageLink center">
        <div class="imageContent center"
             style="background-image:url('<?= $model->getImage($name); ?>');padding-bottom: <?= $padding; ?>"></div>
    </a>
    <!--max-width:<?= Yii::$app->params["thumbWidth"][$size_type]["width"]; ?>px; -->
    <button type="button" class="btn btn-info uploadButton">
        <i class="fa fa-edit"></i> 選擇圖片
        <br/>
        <?= $message; ?>
    </button>
    <?= $form->field($model, $name, [
        'template' => "{input}\n{error}",
    ])->fileInput([
        "style"  => 'height:0px; opacity:0;display:none;',
        'accept' => 'image/png, image/gif, image/jpeg, image/jpg, image/webp, image/svg+xml',
    ]);

    ?>
    <div class="hide">
        <?= $form->field($model, $name)->hiddenInput(["id" => null]);

        ?>
    </div>
</div>