<?php namespace front\components;

use Yii;
use yii\web\IdentityInterface;

class User extends \yii\web\User {

    public function getIsLoggedIn() {
        return !$this->getIsGuest();
    }

    public function login(IdentityInterface $identity, $duration = 0) {
        return parent::login($identity, $duration);
    }
}
