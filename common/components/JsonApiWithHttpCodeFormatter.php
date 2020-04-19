<?php namespace common\components;

use Yii;
use yii\helpers\Json;
use yii\web\ResponseFormatterInterface;

class JsonApiWithHttpCodeFormatter implements ResponseFormatterInterface {

    public function format($response) {
        $response->getHeaders()->set('Content-Type', 'application/json; charset=UTF-8');
        if (($response->data !== null)) {
            $result = $response->data;

            if (($response->getStatusCode() != 200)) {
                if (Yii::$app->tool->isJson($result["message"])) {
                    $result = json_decode($result["message"]);
                } else {
                    $result = $result["message"];
                }
            }
            $result = [
                "data"   => $result,
                "status" => $response->getStatusCode(),
            ];

            $response->content = Json::encode($result);
        }
    }

}
