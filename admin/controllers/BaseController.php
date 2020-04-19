<?php namespace admin\controllers;

use Yii;

class BaseController extends \common\controllers\BaseController {
    public $layout = 'main';
    public $title = '系統管理介面';

    public function behaviors() {

        return \yii\helpers\ArrayHelper::merge([
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'except' => [
                    'top-login',
                    'login',
                    'error'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            [
                'class' => '\admin\components\filters\PermissionFilter',
                'except' => [
                    'top-login',
                    'login',
                    'error',
                    'logout'
                ],
            ],
        ], parent::behaviors());
    }

    public function actionError() {
        $exception = Yii::$app->errorHandler->exception;

        $this->layout = "base";

        if ($exception !== null) {
            return $this->render("@admin/views/error", array(
                "exception" => $exception
            ));
        }
        exit;
    }

}
