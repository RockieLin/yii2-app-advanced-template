<?php namespace common\assets\Theme;

use yii\web\AssetBundle;

class ModernBlueAsset extends AssetBundle {

    public $sourcePath = '@common/assets/Theme/assets';
    public $css = [
        'css/modern-blue.css',
        'css/main.css',
    ];
    public $depends = [
        'common\assets\Theme\CoralBaseAsset'
    ];

}
