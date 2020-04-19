<?php
namespace common\assets\Plugin;

use Yii;
use yii\web\AssetBundle;

class JqueryLoadingAsset extends AssetBundle {

    public $sourcePath = '@common/assets/Plugin/assets';
    public $css = [
        'jquery.loading/jquery.loading.min.css',
    ];
    public $js = [
        'jquery.loading/jquery.loading.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
