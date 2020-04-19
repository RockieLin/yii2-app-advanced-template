<?php namespace common\assets\Theme;

use yii\web\AssetBundle;

class ModernDarkAsset extends AssetBundle {

    public $sourcePath = '@common/assets/Theme/assets';
    public $css = [
        'css/modern-dark.css',
        'css/main.css',
    ];
    public $depends = [
        'common\assets\Theme\CoralBaseAsset'
    ];

}
