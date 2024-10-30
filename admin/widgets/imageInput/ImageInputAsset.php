<?php

namespace admin\widgets\imageInput;

use yii\web\AssetBundle;

class ImageInputAsset extends AssetBundle {

    public $publishOptions = [
        'forceCopy' => true,
    ];
    public $css = [
        'main.css',
    ];

    public $js = [
        'main.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init() {
        parent::init();

        $this->sourcePath = __DIR__ . '/assets';
    }


}
