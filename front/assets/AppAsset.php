<?php namespace front\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "css/custom.css",
    ];
    public $js = [
        "js/main.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

}
