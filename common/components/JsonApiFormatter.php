<?php

namespace common\components;

use Yii;
use yii\helpers\Json;
use yii\web\ResponseFormatterInterface;

class JsonApiFormatter implements ResponseFormatterInterface {

    public function format($response) {
        //print_r($response->getStatusCode());exit;
        $response->getHeaders()->set('Content-Type', 'application/json; charset=UTF-8');
        if (($response->data !== null)) {
            $message = "success";
            $result = $response->data;

            if (($response->getStatusCode() != 200)) {
                if (Yii::$app->tool->isJson($result["message"])) {
                    $message = json_decode($result["message"]);
                } else {
                    $message = $result["message"];
                }
                $result = null;
            }
            $result = [
                "msg" => $message,
                "data" => $result,
                "code" => $response->getStatusCode(),
            ];

            $response->setStatusCode(200);

            $response->content = Json::encode($result);
        }
    }

}
