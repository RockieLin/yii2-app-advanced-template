<?php namespace front\controllers;

use Yii;

class JobController extends \yii\web\Controller {

    public function init() {
        parent::init();
    }

    public function actionClearcache() {
        Yii::$app->cache->flush();
        echo "clear";
        exit;
    }

}

?>