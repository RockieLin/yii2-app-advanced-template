<?php

return [
    'id'                  => 'Admin',
    'basePath'            => dirname(__DIR__),
    'aliases'             => [
        '@npm' => '@vendor/npm-asset',
    ],
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'admin\controllers',
    'defaultRoute'        => 'home/index',
    'modules'             => [
        'log-reader' => [
            'class'   => 'kriss\logReader\Module',
            'layout'  => '/log-reader',
            'aliases' => [
                'Front'       => "@front/runtime/logs/log",
                'Common'      => "@common/runtime/logs/log",
                'Api'         => "@api/runtime/logs/log",
                'Admin'       => "@admin/runtime/logs/log",
            ],
        ],
        'gridview'   => [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'components'          => [
        'authManager'  => [
            //            'class'           => 'yii\rbac\DbManager',
            'class'           => 'admin\components\BackendDbManager',
            'itemTable'       => 'auth_item',
            'itemChildTable'  => 'auth_item_child',
            'assignmentTable' => 'auth_assignment',
            'ruleTable'       => 'auth_rule',
        ],
        'user'         => [
            'class'           => 'admin\components\User',
            'identityClass'   => 'common\models\entities\Admin',
            'enableAutoLogin' => true,
            'loginUrl'        => ["auth/top-login"],
        ],
        'request'      => [
            'enableCookieValidation' => true,
            'enableCsrfValidation'   => true,
            'cookieValidationKey'    => 'cookieValidationKey',
        ],
        'response'     => [
            'class'      => 'yii\web\Response',
            'formatters' => [
                'jsonApiWithHttpCode' => 'common\components\JsonApiWithHttpCodeFormatter',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'base/error',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset'                => [
                    'sourcePath' => null,
                    // do not publish the bundle
                    'js'         => [
                        'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset'       => [
                    'sourcePath' => null,
                    'css'        => [
                        'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'js'         => [
                        'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'
                    ]
                ],
            ],
        ],
        'view'         => [
            'class'           => '\rmrevin\yii\minify\View',
            'enableMinify'    => true,
            'concatCss'       => true,
            // concatenate css
            'minifyCss'       => true,
            // minificate css
            'concatJs'        => true,
            // concatenate js
            'minifyJs'        => true,
            // minificate js
            'minifyOutput'    => true,
            // minificate result html page
            'webPath'         => '@web',
            // path alias to web base
            'basePath'        => '@webroot',
            // path alias to web base
            'minifyPath'      => '@webroot/minify',
            // path alias to save minify result
            'jsPosition'      => [\yii\web\View::POS_END],
            // positions of js files to be minified
            'forceCharset'    => 'UTF-8',
            // charset forcibly assign, otherwise will use all of the files found charset
            'expandImports'   => true,
            // whether to change @import on content
            'compressOptions' => ['extra' => true],
            // options for compress
            'excludeBundles'  => [
                \yii\debug\DebugAsset::class,
                \dosamigos\ckeditor\CKEditorAsset::class,
                //                \common\widgets\imageInput\ImageInputAsset::class,
            ],
        ],
    ],
];
