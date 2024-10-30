<?php
\common\assets\Plugin\ImageUploaderAsset::register($this);
$inputName = (new \ReflectionClass($model))->getShortName()."[images]";
$this->registerJs("$('.input-images').imageUploader({
    preloaded: " . json_encode($model->getPreloadedImageFormat()) . ",
    imagesInputName: '{$inputName}',
    preloadedInputName: '{$inputName}',
    maxSize: 2 * 1024 * 1024,
    maxFiles: 15
});");

if (!isset($size_type) || empty($size_type)) {
    $size_type = "common";
    $message = null;
} else {
    $message = Yii::$app->params["thumbWidth"][$size_type]["width"] . " x " . Yii::$app->params["thumbWidth"][$size_type]["height"];
}
$ratio = Yii::$app->params["thumbWidth"][$size_type]["height"] / Yii::$app->params["thumbWidth"][$size_type]["width"];
if ($ratio == 1) {
    $padding = ($ratio / 2 * 100) . "%";
} else {
    $padding = ($ratio * 100) . "%";
}
$width = 186;
$height = $width * $ratio;

$this->registerCss("
.image-uploader .uploaded .uploaded-image{
    width: {$width}px;
    height: {$height}px;
    cursor: pointer;
}
.image-uploader .uploaded .uploaded-image img{
    object-fit: contain;
}
");

?>
<div class="input-field">
    <div class="input-images" style="padding-top: .5rem;"></div>
</div>