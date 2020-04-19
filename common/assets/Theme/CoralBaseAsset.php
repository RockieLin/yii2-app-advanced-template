<?php namespace common\assets\Theme;

use yii\web\AssetBundle;

class CoralBaseAsset extends AssetBundle {

    public $sourcePath = '@common/assets/Theme/assets';
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
        'icons/pictoicons/css/picto.css',
    ];
    public $js = [
        'js/modernizr/modernizr.js',
        'js/sidebar.main.init.js',
        'js/jquery.autosize-min.js',
        'js/init.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        //'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];

}
