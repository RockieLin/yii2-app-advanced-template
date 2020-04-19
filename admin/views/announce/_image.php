<div class="imageBlock center">
    <label class="control-label"><?= $model->getAttributeLabel($name); ?></label><br/>
    <a href="<?= $model->getImage($name); ?>" target="_blank" class="imageLink">
        <div class="imageContent" style="background-image:url('<?= $model->getImage($name); ?>');padding-bottom: <?=Yii::$app->params["thumbWidth"][$size_type]["height"] / Yii::$app->params["thumbWidth"][$size_type]["width"] * 100;?>%"></div>
    </a>
    <button type="button" class="btn btn-info uploadButton">
        <i class="fa fa-edit"></i> 選擇圖片
        <br/>
        建議尺寸<?= Yii::$app->params["thumbWidth"][$size_type]["width"] . " x " . Yii::$app->params["thumbWidth"][$size_type]["height"]; ?>
    </button>
    <?=
    $form->field($model, $name, [
        'template' => "{input}\n{error}",
    ])->fileInput([
        "style" => 'height:0px; opacity:0;display:none;']);

    ?>
    <div class="hide">
        <?=
        $form->field($model, $name)->hiddenInput();
        ?>
    </div>
</div>