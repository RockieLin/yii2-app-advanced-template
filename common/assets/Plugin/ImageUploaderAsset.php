<?php

namespace common\assets\Plugin;

use yii\web\AssetBundle;

class ImageUploaderAsset extends AssetBundle {
    public $sourcePath = '@common/assets/Plugin/assets/image-uploader';
//        public $publishOptions = [
//        'forceCopy' => true,
//    ];
    public $css = [
        'image-uploader.min.css',
    ];
    public $js = [
        'image-uploader.min.js',
        'Sortable.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

}
