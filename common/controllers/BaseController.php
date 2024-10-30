<?php namespace common\controllers;

use yii\web\Controller;
use Yii;

class BaseController extends Controller {

    public $title = 'Common Title';

    public function init() {
        parent::init();

        Yii::$app->formatter->nullDisplay = '-';

        if (isset($_SERVER['HTTP_SEC_PURPOSE']) && $_SERVER['HTTP_SEC_PURPOSE'] === 'prefetch') {
            echo "no prefetch";
            exit;
        }
    }
}
