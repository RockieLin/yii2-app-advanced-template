<?php

return [
    'id'                  => 'api',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'api\controllers',
    'defaultRoute'        => 'site/index',
    'components'          => [
        'response'     => [
            'class'      => 'yii\web\Response',
            'formatters' => [
                'jsonApi'             => 'common\components\JsonApiFormatter',
                'jsonApiWithHttpCode' => 'common\components\JsonApiWithHttpCodeFormatter',
            ],
        ],
        'request'      => [
            'enableCookieValidation' => false,
            'enableCsrfValidation'   => false,
            'cookieValidationKey'    => 'set random string',
        ],
//        'errorHandler' => [
//            'errorAction' => 'base/error',
//        ],
    ],
];

?>