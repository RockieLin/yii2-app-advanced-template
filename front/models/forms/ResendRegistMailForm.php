<?php namespace front\models\forms;

use yii\base\Model;
use \common\models\entities\Member;

class ResendRegistMailForm extends Model {

    public $username;
    public $type;
    public $email;
    public $user;

    /**
     * Declares the validation rules.
     * The rules state that account and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            [[
            'username'],
                'required'],
            [[
            'username'],
                'email'],
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => "Email",
        );
    }

    public function checkRule() {
        $userModel = Member::findOne(array(
                    "username"    => $this->username,
                    "social_type" => "email"));
        if (!$userModel) {
            $this->addError('username', "查無此Email");
            return false;
        }
        $this->user = $userModel;
        return true;
    }

}
