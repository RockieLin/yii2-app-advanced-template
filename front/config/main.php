<?php
return [
    'id' => 'front',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log'
    ],
    'controllerNamespace' => 'front\controllers',
    'defaultRoute' => 'site/index',
    'components' => [
        'user' => [
            'class' => 'front\components\User',
            'identityClass' => 'common\models\entities\Member',
            'enableAutoLogin' => true,
            'loginUrl' => [
                "user/gologin"
            ],
        ],
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'set random string',
        ],
        'errorHandler' => [
            'errorAction' => 'base/error',
        ],
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => [
                'en',
                'en-*' => 'en',
                'zh-TW',
                'zh-CN',
            ],
            'enableDefaultLanguageUrlCode' => true,
            'enableLanguagePersistence' => true,
            'rules' => require(__DIR__ . '/routes.php'),
        ],
        'socialLogin' => [
            'class' => 'front\components\SocialLogin',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null, // do not publish the bundle
                    'js' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,
                    'css' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css'
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js",
                        'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js'
                    ]
                ],
            ],
        ],
        'view' => [
            'class' => '\rmrevin\yii\minify\View',
            'enableMinify' => true,
            'concatCss' => true, // concatenate css
            'minifyCss' => true, // minificate css
            'concatJs' => true, // concatenate js
            'minifyJs' => true, // minificate js
            'minifyOutput' => true, // minificate result html page
            'webPath' => '@web', // path alias to web base
            'basePath' => '@webroot', // path alias to web base
            'minifyPath' => '@webroot/minify', // path alias to save minify result
            'jsPosition' => [\yii\web\View::POS_END], // positions of js files to be minified
            'forceCharset' => 'UTF-8', // charset forcibly assign, otherwise will use all of the files found charset
            'expandImports' => true, // whether to change @import on content
            'compressOptions' => ['extra' => true], // options for compress
            'excludeBundles' => [
                \yii\debug\DebugAsset::class, // exclude this bundle from minification
                \yii\bootstrap\BootstrapAsset::class,
                \front\assets\BaseAsset::class,
            ],
        ],
    ],
];

?>