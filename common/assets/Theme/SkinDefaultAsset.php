<?php namespace common\assets\Theme;

use yii\web\AssetBundle;

class SkinDefaultAsset extends AssetBundle {

    public $sourcePath = '@common/assets/Theme/assets';
    public $css = [
        'css/skin-default.css',
        'css/main.css',
    ];
    public $depends = [
        'common\assets\Theme\CoralBaseAsset'
    ];

}
