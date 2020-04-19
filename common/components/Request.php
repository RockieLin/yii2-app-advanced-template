<?php

namespace common\components;


class Request extends \yii\web\Request {

    public function getRemoteIP() {
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
}