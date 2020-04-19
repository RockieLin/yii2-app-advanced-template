<?php namespace admin\components;


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

        return parent::can($permissionName, $params, $allowCaching);
    }

}
