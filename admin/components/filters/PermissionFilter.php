<?php namespace admin\components\filters;

use Yii;
use yii\base\ActionFilter;

class PermissionFilter extends ActionFilter {

    public function beforeAction($action) {
        $_controller = Yii::$app->controller;
        $_user = Yii::$app->user;

        //直接給所有權限
        if ($_user->identity->role == 1) {
            return true;
        }

        //角色權限
        $controllerName = strtolower($_controller->id);
        $actionName = strtolower($_controller->action->id);
        if (strtolower($controllerName . "." . $actionName) == "user.edit" || strtolower($controllerName . "." . $actionName) == "site.index") {
            return true;
        }
        
        if ($_user->can(strtolower($controllerName . ".*")) || $_user->can(strtolower($controllerName . "." . $actionName))) {
            return true;
        }
        throw new \yii\web\HttpException(400, "您的權限不足");

        return false;
    }

}

?>