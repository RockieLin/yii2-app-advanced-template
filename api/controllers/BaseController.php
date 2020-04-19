<?php namespace api\controllers;

use Yii;
use yii\web\HttpException;

class BaseController extends \common\controllers\BaseController {

    public $title = "API";
    public $layout = false;
    public $brand;

    public function init() {
        parent::init();
        Yii::$app->response->format = 'jsonApiWithHttpCode';
        $this->enableCsrfValidation = false;
    }


    public function checkParams($arr = []) {
        if ($arr) {
            foreach ($arr as $v) {
                if (Yii::$app->request->get($v) === null) {
                    throw new HttpException(400, "缺少參數-{$v}");
                }
            }
        }
    }

    public function checkPostParams($arr = []) {
        if ($arr) {
            foreach ($arr as $v) {
                if (Yii::$app->request->post($v) === null) {
                    throw new HttpException(400, "缺少參數-{$v}");
                }
            }
        }
    }

    /**
     * 檢查是否有缺少的Json欄位
     * @param array $arr
     * @return mixed
     * @throws HttpException
     */
    public function checkJsonParams($arr = []) {
        $json_array = json_decode(Yii::$app->request->getRawBody());
        if ($arr) {
            foreach ($arr as $v) {
                if (!isset($json_array->$v) || empty($json_array->$v)) {
                    throw new HttpException(400, "缺少參數-{$v}");
                }
            }
        }
        return $json_array;
    }
}
