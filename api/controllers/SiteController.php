<?php namespace api\controllers;
use Yii;

class SiteController extends \common\controllers\BaseController {

    public function actionIndex() {
        return $this->render("index");
    }

    public function actionClearcache() {
        Yii::$app->cache->flush();
        echo "clear";
        exit;
    }
}

?>
