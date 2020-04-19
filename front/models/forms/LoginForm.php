<?php namespace front\models\forms;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model {

    public $code = 0;
    public $username;
    public $password;
    public $rememberMe = true;
    public $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [[
            'username',
            'password'],
                'required'],
            ['rememberMe',
                'boolean'],
            ['username',
                'validateUser'],
            ['username',
                'email'],
            ['username',
                'validateEmailVerify'],
            ['password',
                'validatePassword'],
        ];
    }

    public function attributeLabels() {
        return [
            'username' => "Email",
            'password' => "密碼",
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {

        $user = $this->getUser();
        if ($user) {
            if (!$user->validatePassword($this->password)) {
                $this->addError('password', "密碼錯誤");
            }
        }
    }

    public function validateUser($attribute, $params) {
        $user = $this->getUser();
        if (!$user) {
            $this->addError('username', "查無此帳號");
        }
    }

    public function validateEmailVerify($attribute, $params) {
        $user = $this->getUser();
        if ($user) {
            if ($user->status != 2) {
                $this->addError('username', "此帳號尚未通過Email認證或停用");
                $this->code = 1;
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), Yii::$app->params["cookieExpired"]);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser() {
        if ($this->_user === false) {
            $this->_user = \common\models\entities\Member::findByUsername($this->username);
        }

        return $this->_user;
    }

}
