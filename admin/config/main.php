<?php
//log檔
$logFils = [];
foreach (preg_grep('/^([^.])/', scandir(Yii::getAlias("@front/runtime/logs"))) as $_val) {
    $logFils["Front_" . $_val] = Yii::getAlias("@front/runtime/logs/{$_val}");
}
foreach (preg_grep('/^([^.])/', scandir(Yii::getAlias("@admin/runtime/logs"))) as $_val) {
    $logFils["Admin_" . $_val] = Yii::getAlias("@admin/runtime/logs/{$_val}");
}
foreach (preg_grep('/^([^.])/', scandir(Yii::getAlias("@common/runtime/logs"))) as $_val) {
    $logFils["Common" . $_val] = Yii::getAlias("@common/runtime/logs/{$_val}");
}
foreach (preg_grep('/^([^.])/', scandir(Yii::getAlias("@api/runtime/logs"))) as $_val) {
    $logFils["API_" . $_val] = Yii::getAlias("@api/runtime/logs/{$_val}");
}
arsort($logFils);
return [
    'id' => 'Admin',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@npm' => '@vendor/npm-asset',
    ],
    'bootstrap' => ['log'],
    'controllerNamespace' => 'admin\controllers',
    'defaultRoute' => 'member/index',
    'modules' => [
        'log-reader' => [
            'class' => 'kriss\logReader\Module',
            'aliases' => $logFils,
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'components' => [
        'authManager' => [
//            'class'           => 'yii\rbac\DbManager',
            'class' => 'admin\components\BackendDbManager',
            'itemTable' => 'auth_item',
            'itemChildTable' => 'auth_item_child',
            'assignmentTable' => 'auth_assignment',
            'ruleTable' => 'auth_rule',
        ],
        'user' => [
            'class' => 'admin\components\User',
            'identityClass' => 'common\models\entities\Admin',
            'enableAutoLogin' => false,
            'loginUrl' => ["auth/top-login"],
        ],
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'set random string',
        ],
        'errorHandler' => [
            'errorAction' => 'base/error',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null, // do not publish the bundle
                    'js' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,
                    'css' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css'
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'js' => [
                        'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js'
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
            ],
        ],
    ],
];
