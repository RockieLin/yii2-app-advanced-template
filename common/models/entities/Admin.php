<?php namespace common\models\entities;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $image
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class Admin extends \common\models\entities\Base implements IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [
                [
                    'username',
                    'password',
                    'name',
                    'role',
                ],
                'required'
            ],
            [
                [
                    'username'
                ],
                'unique'
            ],
            [
                [
                    'username'
                ],
                'email'
            ],
            [
                [
                    'status',
                    'role',
                    'updated_at',
                    'created_at'
                ],
                'integer'
            ],
            [
                [
                    'username'
                ],
                'string',
                'max' => 50
            ],
            [
                [
                    'password'
                ],
                'string',
                'min' => 6,
                'max' => 40
            ],
            [
                [
                    'password'
                ],
                'match',
                'not' => true,
                'pattern' => '/[^A-Za-z0-9@_]/',
                'message' => '密碼僅限英數字或符號[@][_]',
            ],
            [
                [
                    'name'
                ],
                'string',
                'max' => 80
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '編號',
            'username' => '帳號',
            'password' => '密碼',
            'name' => '名稱',
            'role' => '角色',
            'status' => '啟用狀態',
            'updated_at' => '最後修改',
            'created_at' => '建立時間',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->password = md5($this->password);
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * Finds user by username
     *
     * @param  string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    public function validatePassword($password) {
        return $this->password === md5($password);
    }

    public function getAuthKey() {
        return $this->id . $this->username;
    }

    public function validateAuthKey($authKey) {
        return ($this->id . $this->username == $authKey) ? true : false;
    }

    public static function findIdentityByAccessToken($token, $type = NULL) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByPasswordResetToken($token) {
        throw new NotSupportedException('findByPasswordResetToken" is not implemented.');
    }

    public function getNotifications() {
        return $this->hasMany(Notification::className(), ['admin_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes) {
        //寫入權限
        $this->revokeRole();
        $auth = Yii::$app->authManager;
        $_role = $auth->getRole($this->role);
        $auth->assign($_role, $this->id);

        return parent::afterSave($insert, $changedAttributes);
    }

    //移除該用戶所有權限
    public function revokeRole() {
        $auth = Yii::$app->authManager;
        foreach ($auth->getRolesByUser($this->id) as $key => $value) {
            $auth->revoke($value, $this->id);
        }
    }

    public static function getRoles() {
        $result = [];
        $roles = Yii::$app->authManager->getRoles();
        foreach ($roles as $val) {
            $result[$val->name] = $val->description;
        }

        return $result;
    }

}
