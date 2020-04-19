<?php
//開debug模式
if (file_exists(__DIR__ . '/develop.me')) {

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
}

?>