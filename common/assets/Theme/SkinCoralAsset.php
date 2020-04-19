<?php namespace common\assets\Theme;

use yii\web\AssetBundle;

class SkinCoralAsset extends AssetBundle {

    public $sourcePath = '@common/assets/Theme/assets';
    public $css = [
        'css/skin-coral.css',
        'css/main.css',
    ];
    public $depends = [
        'common\assets\Theme\CoralBaseAsset'
    ];

}
