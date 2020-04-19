<?php
namespace admin\models\forms;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class RoleModel extends Model {

    public $id, $name, $error;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [
                [
                    'id',
                    'name',
                ],
                'required'],
            [
                [
                    'id',
                ],
                'integer'],
        ];
    }

    public function attributeLabels() {
        return [
            'id'    => '角色編號',
            'name'  => '角色名稱',
            'error' => '錯誤',
        ];
    }

}
