<?php
$params = require(__DIR__ . '/params.php');
$params["environmentNotice"] = "Sandbox";   //識別開發或正式環境
$params["staticFileUrl"] = "http://common.example.com"; //圖檔域名

//DB
$dbHost = "127.0.0.1";
$dbName = "advanced-template";
$dbUsername = "username";
$dbPassword = "password";

$other_config = [];

//允許debug panel 和 gii的IP
$_ips = ["*"];
//$_ips = [];

//開啟debug panel
$other_config['bootstrap'][] = 'debug';
$other_config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    'allowedIPs' => $_ips
];
//開始gii
$other_config['bootstrap'][] = 'gii';
$other_config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    'allowedIPs' => $_ips
];

$config = [
    'id' => 'common',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'common\controllers',
    'defaultRoute' => 'site/index',
    'language' => 'zh-TW',
    'sourceLanguage' => 'en-US',
    'timezone' => 'Asia/Taipei',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset'
    ],
    'controllerMap' => [
        'migration' => [
            'class' => 'bizley\migration\controllers\MigrationController',
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "mysql:host={$dbHost};dbname={$dbName}",
            'username' => $dbUsername,
            'password' => $dbPassword,
            'charset' => 'utf8mb4',
            'enableSchemaCache' => true,
            'enableQueryCache' => true,
            'queryCacheDuration' => 86400 * 30,
            'queryCache' => 'cache',
        ],
        'cache'                => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'traceLevel' => 3,
            'targets' => [
                "info" => [
                    'class' => 'yii\log\FileTarget',
                    'maxFileSize' => 10240 * 1, //10MB
                    'maxLogFiles' => '50',
                    'dirMode' => 0777,
                    'fileMode' => 0777,
                    'except' => [
                        'yii\web\Session:*',
                        'yii\db\*',
                        'yii\web\User:*',
                        'yii\debug\Module:*'
                    ],
                    'levels' => [
                        'info',
                    ],
                    'logVars' => [],
                    'logFile' => '@app/runtime/logs/log.' . date("Ymd", time()),
                ],
                "error" => [
                    'class' => 'yii\log\FileTarget',
                    'maxFileSize' => 10240 * 1, //10MB
                    'maxLogFiles' => '50',
                    'dirMode' => 0777,
                    'fileMode' => 0777,
                    'except' => [
                        'yii\debug\Module:*',
                        'yii\web\HttpException:401',
                        'yii\web\HttpException:404',
                    ],
                    'levels' => [
                        'error',
                        'warning'
                    ],
                    'logVars' => ['_GET', '_POST', '_SERVER.argv'],
                    'logFile' => '@app/runtime/logs/log.' . date("Ymd", time()),
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'rules'           => require(__DIR__ . '/routes.php'),
        ],
        'i18n' => [
            'translations' => [
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
//                    'sourceLanguage' => 'zh-TW',
                    'basePath' => '@yii/messages'
                ],
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],
        'request' => [
            'class' => 'common\components\Request',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'set random string',
            'secureHeaders' => [
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_REAL_IP',
                'X-Forwarded-For',
                'X-Forwarded-Host',
                'X-Forwarded-Proto',
                'X-Proxy-User-Ip',
                'cf-ipcountry',
                'cf-connecting-ip',
            ],
            'ipHeaders' => [
                'cf-connecting-ip',
                'X-Proxy-User-Ip',
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'SMTP host',
                'username' => 'SMTP name',
                'password' => 'SMTP password',
                'port' => '25',
                'encryption' => 'tls',
            ],
            'viewPath' => '@common/mail',
        ],
        'tool' => [
            'class' => 'common\components\Tool',
        ],
        'authClientCollection' => [
            'class'   => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'name'         => 'facebook',
                    'class'        => 'yii\authclient\clients\Facebook',
                    'clientId'     => "your clientId",
                    'clientSecret' => "your clientSecret",
                    'scope'        => 'email,public_profile',
                    'authUrl'      => 'https://www.facebook.com/dialog/oauth?display=popup',
                    'viewOptions'  => [
                        'popupWidth'  => 500,
                        'popupHeight' => 450,]
                ],
                'google'   => [
                    'name'         => 'google',
                    'class'        => 'yii\authclient\clients\Google',
                    'clientId'     => "your clientId",
                    'clientSecret' => "your clientSecret",
                    'viewOptions'  => [
                        'popupWidth'  => 500,
                        'popupHeight' => 450,]
                ],
            ],
        ],
    ],
    'params' => $params,
];

return yii\helpers\ArrayHelper::merge(
    $config, $other_config
);

?>
