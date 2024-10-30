<?php namespace admin\controllers;

use common\models\entities\Category;
use common\models\entities\Member;
use common\models\entities\Site;
use Yii;

class HomeController extends BaseController {

    public function init() {
        parent::init();
    }

    public function actionIndex() {
        return $this->render('index', []);
    }
}
