<?php namespace common\models\entities;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $social_type
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $name
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class Member extends \common\models\entities\Base implements IdentityInterface {

    public $captcha;

    const STATUS_DISABLE = 0;
    const STATUS_EMAIL_NOT_VALIDATE = 1;
    const STATUS_EMAIL_VALIDATE = 2;

    public $chkpassword;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[
            'social_type',
            'username',
            'password',
            'email',
            'name',
            'status'],
                'required'],
            [[
            'status',
            'updated_at',
            'created_at'],
                'integer'],
            [[
            'social_type'],
                'string',
                'max' => 10],
            [[
            'username'],
                'string',
                'max' => 255],
            [[
            'password'],
                'string',
                'min' => 6,
                'max' => 40],
            [[
            'email',
            'name'],
                'string',
                'max' => 100],
            [[
            'username'],
                'unique',
                'message' => '此帳號已被使用'],
            [[
            'password'],
                'match',
                'not'     => true,
                'pattern' => '/[^A-Za-z0-9@_]/',
                'message' => '密碼僅限英數字或符號[@][_]',
            ],
            array(
                'username',
                'match',
                'not'     => true,
                'pattern' => '/[^A-Za-z0-9@_.]/',
                'message' => '僅限英數字或「_」',
                'on'      => ['insert',
                    'fb-insert'],
            ),
            array(
                'username',
                'email',
                'on' => ['insert',
                    'fb-insert'],
            ),
            ['captcha',
                'captcha',
                'on' => ['insert'],],
            ['captcha',
                'required',
                'on' => ['insert'],],
            array(
                'email',
                'email',
            ),
            array(
                'chkpassword',
                'compare',
                'compareAttribute' => 'password',
                'on'               => ['insert',
                    'updatepwd'],
                'message'          => '確認密碼與密碼不相符',
            ),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'          => 'ID',
            'social_type' => '帳號類型',
            'username'    => '帳號',
            'password'    => '密碼',
            'chkpassword' => "確認密碼",
            'email'       => 'Email',
            'name'        => '真實姓名',
            'status'      => '狀態',
            'updated_at'     => '最後修改',
            'created_at'  => '註冊時間',
            "captcha"     => "圖形驗證碼"
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->password = md5($this->password);
        } else {
            $this->username = $this->getOldAttribute("username");
            $this->email = $this->getOldAttribute("email");
            $this->social_type = $this->getOldAttribute("social_type");
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
     * @param  string      $username
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

    public function getAuthKey() {
        return $this->id . $this->username;
    }

    public function validateAuthKey($authKey) {
        return ($this->id . $this->username == $authKey) ? true : false;
    }

    public static function findIdentityByAccessToken($token, $type = NULL) {
        $token = MemberToken::findOne(['token' => $token]);

        return (isset($token) && ($token->expired_time > time()) && ($token->member->status == 1)) ? $token->member : null;
    }

    public static function findByPasswordResetToken($token) {
        throw new NotSupportedException('findByPasswordResetToken" is not implemented.');
    }

    public function validatePassword($password) {
        return $this->password === md5($password);
    }

}
