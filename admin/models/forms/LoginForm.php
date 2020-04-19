<?php namespace admin\models\forms;

use Yii;
use yii\base\Model;
use common\models\entities\Admin;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $rememberMe = true;
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [[
            'username',
            'password'],
                'required'],
            // password is validated by validatePassword()
            ['password',
                'validatePassword'],
            ['username',
                'validateStatus'],
            array(
                'username',
                'email',
            ),
        ];
    }

    public function attributeLabels() {
        return [
            'username' => '帳號',
            'password' => '密碼',
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
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '密碼錯誤');
            }
        }
    }

    public function validateStatus($attribute, $params) {
        $user = $this->getUser();
        if ($user) {
            if ($user->status != 1) {
                $this->addError('username', '此帳號未啟用');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
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
            $this->_user = Admin::findByUsername($this->username);
        }

        return $this->_user;
    }

}
