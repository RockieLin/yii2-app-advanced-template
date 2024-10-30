<?php
//環境設定
$env = null;
if (file_exists(__DIR__ . '/.env.prod')) {
    $env = "prod";
} elseif (file_exists(__DIR__ . '/.env.sandbox')) {
    $env = "sandbox";
} else {
    echo ".env.xxx file not setting";
    exit;
}

defined('ENV') or define('ENV', $env); //dev, stage, prod

$params = require (__DIR__ . '/setting.' . $env . '.php');

//開debug模式
if (file_exists(__DIR__ . '/develop.me') && in_array(getRemoteIP(), $params['whiteListIp'])) {

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
}

function getRemoteIP() {
    $ipAddress = null;
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipList = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $ip_array = explode(',', $ipList);
        if (is_array($ip_array)) {
            $ipAddress = $ip_array[0];
        }
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ipAddress = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    } elseif (isset($_SERVER['SERVER_NAME'])) {
        $ipAddress = $_SERVER['SERVER_NAME'];
    }
    return $ipAddress;
}

?>
