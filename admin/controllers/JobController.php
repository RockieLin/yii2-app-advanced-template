<?php namespace admin\controllers;

use Yii;

class JobController extends \yii\console\Controller {

    public function init() {
        parent::init();
        $this->layout = false;
        ini_set('max_execution_time', 3000);
    }

    public function actionClearcache() {
        Yii::$app->cache->flush();
        echo "clear";
        exit;
    }

    /**
     * 刪除逾期的EmailCheckCode
     * 3小時跑一次
     */
    public function actionClearEmailCheckcode() {
        $expireTime = time() - (Yii::$app->params["checkCodeExpired"]);

        \common\models\entities\EmailCheckCodes::deleteAll("created_at < {$expireTime}");

        echo Yii::$app->controller->action->id . " Done!! \n\r";exit;
    }

    /**
     * 刪除逾期未通過Email認證的User
     * 3小時跑一次
     */
    public function actionClearExpireUser() {
        $expireTime = time() - (Yii::$app->params["checkCodeExpired"]);
        \common\models\entities\Member::deleteAll("status = 1 AND created_at<{$expireTime}");

        echo Yii::$app->controller->action->id . " Done!! \n\r";exit;
    }

}

?>