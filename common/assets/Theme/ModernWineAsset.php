<?php namespace common\assets\Theme;

use yii\web\AssetBundle;

class ModernWineAsset extends AssetBundle {

    public $sourcePath = '@common/assets/Theme/assets';
//    public $publishOptions = [
//        'forceCopy' => true,
//    ];

    public $css = [
        'css/modern-wine.css',
        'css/main.css',
    ];
    public $depends = [
        'common\assets\Theme\CoralBaseAsset'
    ];

}
