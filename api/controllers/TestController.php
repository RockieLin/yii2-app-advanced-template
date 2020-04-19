<?php namespace api\controllers;

use Yii;

class TestController extends BaseController {

    public function actionIndex() {
        return "api example";
    }

    public function actionData() {
        return [
            "a" => 1,
            "b" => 2,
            "c" => 3,
            "d" => 4,
        ];
    }
}

?>
