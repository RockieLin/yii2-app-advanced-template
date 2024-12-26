<?php
$params = yii\helpers\ArrayHelper::merge(require(__DIR__ . '/params.php'), require(__DIR__ . '/setting.' . ENV . '.php'));

$other_config = [];

if (YII_DEBUG === true) {
    //開啟debug panel
    $other_config['bootstrap'][] = 'debug';
    $other_config['modules']['debug'] = [
        'class'       => 'yii\debug\Module',
        'allowedIPs'  => $params["whiteListIp"],
        'historySize' => 200
    ];
    //開啟gii
    $other_config['bootstrap'][] = 'gii';
    $other_config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'allowedIPs' => $params["whiteListIp"]
    ];
}

$db = [
    'class'               => 'yii\db\Connection',
    'commandClass'        => "\common\components\MySqlCommand",
    'dsn'                 => "mysql:host={$params["db"]["dbHost"]};dbname={$params["dbName"]}",
    'username'            => $params["db"]["dbUsername"],
    'password'            => $params["db"]["dbPassword"],
    'charset'             => 'utf8mb4',
    'enableSchemaCache'   => $params["enableCache"],
    'schemaCacheDuration' => 86400 * 10,
    'enableQueryCache'    => $params["enableCache"],
    'queryCacheDuration'  => 86400,
];
$toolDb = [
    'class'               => 'yii\db\Connection',
    'commandClass'        => "\common\components\MySqlCommand",
    'dsn'                 => "mysql:host={$params["db"]["dbHost"]};dbname={$params["toolDbName"]}",
    'username'            => $params["db"]["dbUsername"],
    'password'            => $params["db"]["dbPassword"],
    'charset'             => 'utf8mb4',
    'enableSchemaCache'   => $params["enableCache"],
    'schemaCacheDuration' => 86400 * 10,
    'enableQueryCache'    => $params["enableCache"],
    'queryCacheDuration'  => 86400,
];


$config = [
    'id'                  => 'common',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'common\controllers',
    'defaultRoute'        => 'site/index',
    'language'            => 'zh',
    'sourceLanguage'      => 'en-US',
    'timezone'            => 'Asia/Taipei',
    'vendorPath'          => dirname(dirname(__DIR__)) . '/vendor',
    'aliases'             => [
        '@bower' => '@vendor/bower-asset'
    ],
    'components'          => [
        //預設Master主連線
        'db'         => $db,
        'log'        => [
            'traceLevel' => 5,
            'targets'    => [
                "info"  => [
                    'class'        => 'yii\log\FileTarget',
                    'maxFileSize'  => 10240 * 5, //50MB
                    'maxLogFiles'  => '10',
                    'dirMode'      => 0777,
                    'fileMode'     => 0777,
                    'rotateByCopy' => false,
                    'except'       => [
                        'yii\web\Session:*',
                        'yii\db\*',
                        'yii\web\User:*',
                        'yii\debug\Module:*'
                    ],
                    'levels'       => [
                        'info',
                    ],
                    'logVars'      => ['_GET', '_POST', '_SERVER.HTTP_HOST', '_SERVER.REQUEST_URI'],
                    'logFile'      => '@app/runtime/logs/log.' . date("Ymd-H", time()),
                ],
                "error" => [
                    'class'        => 'yii\log\FileTarget',
                    'maxFileSize'  => 10240 * 5, //50MB
                    'maxLogFiles'  => '10',
                    'dirMode'      => 0777,
                    'fileMode'     => 0777,
                    'rotateByCopy' => false,
                    'except'       => [
                        'yii\debug\Module:*',
                        'yii\i18n\PhpMessageSource:*',
                        'yii\web\HttpException:401',
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:406',
                        'yii\web\HttpException:503',
                        'yii\web\HttpException:505',
                        'yii\web\HttpException:510',
                    ],
                    'levels'       => [
                        'error',
                        'warning',
                    ],
                    'logVars'      => ['_GET', '_POST', '_SERVER.argv', '_SERVER.HTTP_HOST', '_SERVER.REQUEST_URI'],
                    'logFile'      => '@app/runtime/logs/log.' . date("Ymd-H", time()),
                ],
            ],
        ],
        'request'    => [
            'class'                  => 'common\components\Request',
            'enableCookieValidation' => true,
            'enableCsrfValidation'   => true,
            'cookieValidationKey'    => 'cookieValidationKey',
            'secureHeaders'          => [
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED_PROTO',
                'HTTP_X_REAL_IP',
                'X-Forwarded-For',
                'X-Forwarded-Host',
                'X-Forwarded-Proto',
                'X-Proxy-User-Ip',
                'cf-ipcountry',
                'cf-connecting-ip',
            ],
            'ipHeaders'              => [
                'cf-connecting-ip',
                'X-Proxy-User-Ip',
                'X-Forwarded-For',
            ],
            'secureProtocolHeaders'  => [
                'X-Forwarded-Proto' => ['https'],
                'Front-End-Https'   => ['on'],    //Azure
                'cf-visitor'        => ['{"scheme":"https"}',] //cloudflare
            ]
        ],
        'response'   => [
            'class'      => 'yii\web\Response',
            'formatters' => [
                // 包網自訂api json格式
                //                'jsonApi'             => 'common\components\JsonApiFormatter',
                'jsonApiWithHttpCode' => 'common\components\JsonApiWithHttpCodeFormatter',
            ],
        ],
        'redis'      => [
            'class'             => 'yii\redis\Connection',
            'hostname'          => $params["redis"]["redisHost"],
            'port'              => $params["redis"]["redisPort"],
            'password'          => $params["redis"]["redisPassword"],
            'database'          => $params["redis"]["redisCacheDb"],
            'connectionTimeout' => -1,
        ],
        'session'    => [
            'keyPrefix'    => 'session_',
            'class'   => 'yii\redis\Session',
            'timeout' => 60 * 60 * 24 * 31,
            'redis'   => [
                'class'             => 'yii\redis\Connection',
                'hostname'          => $params["redis"]["redisHost"],
                'port'              => $params["redis"]["redisPort"],
                'password'          => $params["redis"]["redisPassword"],
                'database'          => $params["redis"]["redisSessionDb"],
                'connectionTimeout' => -1,
            ]
        ],
        'cache'      => [
            'class'           => 'yii\redis\Cache',
            'keyPrefix'       => $params["siteIdentity"] . '_cache:',
            'redis'           => 'redis',
            'serializer'      => ['igbinary_serialize', 'igbinary_unserialize'],
            'defaultDuration' => $params["enableCache"] ? (43200) : 1,
        ],
//        'cache'         => [
//            'class'           => 'yii\caching\FileCache',
//            'cachePath'       => '@common/runtime/cache',
//            'defaultDuration' => 0,
//        ],
        'mailer'     => [
            'class'    => 'common\components\Mailer',
            'viewPath' => '@common/mail',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => require(__DIR__ . '/routes.php'),
        ],
        'i18n'       => [
            'translations' => [
                'yii' => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'basePath'       => '@yii/messages'
                ],
            ],
        ],
        'formatter'  => [
            'class'       => 'yii\i18n\Formatter',
            'nullDisplay' => '-',
        ],
        'tool'       => [
            'class' => 'common\components\Tool',
        ],
        'consoleRunner' => [
            'class' => 'vova07\console\ConsoleRunner',
            'file'  => '@app/../yii' // or an absolute path to socket file
        ],
    ],
    'params'              => $params,
];

//開啟特定log level
foreach ($config["components"]["log"]["targets"] as $_key => $_val) {
    if (!in_array($_key, $params["logLevel"])) {
        unset($config["components"]["log"]["targets"][$_key]);
    }
}

return yii\helpers\ArrayHelper::merge($config, $other_config);

?>
