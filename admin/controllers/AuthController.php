<?php

namespace admin\controllers;

use Yii;
use common\models\entities\Admin;
use admin\models\forms\LoginForm;
use yii\helpers\Url;

class AuthController extends BaseController {

    public function init() {
        parent::init();
    }


    public function actionLogin() {
        $this->layout = false;
        $this->title = '管理後台';
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionTopLogin() {
        $this->layout = false;
        return $this->render('top-login', [
            "url" => "/auth/login"
        ]);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->redirect(Url::toRoute('auth/login'));
    }
}
