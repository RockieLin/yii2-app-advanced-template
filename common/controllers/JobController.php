<?php namespace common\controllers;

use Yii;

/**
 * 放cronjob程式
 * Class JobController
 * @package common\controllers
 */
class JobController extends \yii\console\Controller {

    public function actionClearcache() {
        Yii::$app->cache->flush();
        echo "clear";
        exit;
    }


    /**
     * 刪除逾期的EmailCheckCode
     * 3小時跑一次
     * http://admin.aws.driways.com.tw/job/clear-email-checkcode
     */
    public function actionClearEmailCheckcode() {
        $expireTime = time() - (Yii::$app->params["checkCodeExpired"]);

        \common\models\entities\EmailCheckCodes::deleteAll("created_at < {$expireTime}");

        echo Yii::$app->controller->action->id . " Done!! \n\r";
        exit;
    }

}

?>