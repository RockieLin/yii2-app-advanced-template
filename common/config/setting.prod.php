<?php

return [
    //專案代號
    'siteName'                 => 'siteName',
    "siteIdentity"             => "siteIdentity",
    "staticFileUrl"            => "https://common.xxx.com",
    "apiUrl"          => "https://api.xxx.com",
    "frontUrl"        => "https://xxx.com",
    "backendUrl"           => "https://xxx.com",
    "environmentNotice"        => "",
    "db"                       => [
        //master
        "dbHost"     => '127.0.0.1',
        "dbUsername" => "dbUsername",
        "dbPassword" => "dbPassword",
    ],
    "dbName"                   => "dbName",
    "redis"                    => [
        "redisPassword"  => "xxxxx",
        "redisHost"      => "127.0.0.1",
        "redisPort"      => 1234,
        "redisCacheDb"   => 1,
        "redisSessionDb" => 2,
    ],
    "enableCache"              => true,
    //啟用停用cache開關
    "hostIp"                   => "127.0.0.1",
    //本主機IP (參考用)
    "whiteListIp"              => [
    ],
    //白名單IP
    "logLevel"                 => [
        "info",
        "error"
    ],
    //log紀錄層級 info, error
];
