<?php namespace api\components\filters;

use Yii;
use yii\base\ActionFilter;
use yii\web\HttpException;
use common\models\entities\Brand;

class AuthFilter extends ActionFilter {
    /**
     * 400 缺少必要的参数
     * 401 OpCode验证有误
     * 402 IP不允许存取
     * 403 授权码有误
     * @throws HttpException
     */
    public function beforeAction($action) {
        $_controller = Yii::$app->controller;

        $token = Yii::$app->request->get("token");
        if (empty($token)) {
            throw new HttpException(400, "parameter token required");
        }

        throw new HttpException(403, "token invalid");

        return true;
    }
}
