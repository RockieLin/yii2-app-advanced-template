<?php namespace common\controllers;

use yii\web\Controller;
use Yii;

class SiteController extends BaseController {

    public function init() {
        parent::init();
        $this->layout = "main";
    }

    public function actionIndex() {
        return $this->render("index");
    }

}
