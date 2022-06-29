<?php namespace admin\components;
use Yii;

class User extends \yii\web\User {

    /**
     * 
     * 管理員就全給過
     * @param type $permissionName
     * @param type $params
     * @param type $allowCaching
     * @return boolean
     */
    public function can($permissionName, $params = [], $allowCaching = true) {
        //直接給所有權限
        if ($this->identity->role == 1) {
            return true;
        }

        $permissions = $this->getPermissions($this->identity->role);

        $split = explode(".", $permissionName);

        $check1 = strtolower($split[0] . ".*");

        foreach ($permissions as $_name => $val) {
            if (in_array($_name, [$check1, $permissionName])) {
                return true;
            }
        }
        return false;
    }

    public function getPermissions($role) {
        $key = "tmp_permission_{$role}";

        if (isset(Yii::$app->params[$key])) {
            return Yii::$app->params[$key];
        }

        Yii::$app->params[$key] = $this->authManager->getPermissionsByRole($this->identity->role);

        return Yii::$app->params[$key];
    }

}
