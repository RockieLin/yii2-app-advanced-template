<?php namespace front\controllers;

use Yii;

class BaseController extends \common\controllers\BaseController {

    public $layout = 'main';
    public $title = "前台";

    public function actionError() {
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {

            return $this->render("@front/views/error", array(
                "exception" => $exception
            ));
        }
    }
}
