<?php namespace common\controllers;

use yii\web\Controller;
use Yii;

class BaseController extends Controller {

    public $title = 'Common Title';

    public function init() {
        parent::init();

        if (Yii::$app->language == "zh-CN") {
            //簡中轉換
            Yii::$app->response->on(\yii\web\Response::EVENT_BEFORE_SEND, function (\yii\base\Event $Event) {
                $response = $Event->sender;
                if ($response->format === \yii\web\Response::FORMAT_HTML) {
                    if (!empty($response->data)) {
                        $response->data = Yii::$app->tool->twTocn($response->data);
                    }

                    if (!empty($response->content)) {
                        $response->content = Yii::$app->tool->twTocn($response->content);
                    }

                    $response->headers->set('Cache-Control', 'no-transform');
                }
            });
        }
    }
}
