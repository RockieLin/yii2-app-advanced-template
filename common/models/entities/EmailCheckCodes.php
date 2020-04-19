<?php namespace common\models\entities;

use Yii;

/**
 * This is the model class for table "email_check_codes".
 *
 * @property integer $id
 * @property string $member_id
 * @property string $check_code
 * @property string $email
 * @property string $type
 * @property string $other
 * @property integer $created_at
 */
class EmailCheckCodes extends \common\models\entities\Base {

    const TYPE_EMAIL_VALIDATION = "email";
    const TYPE_FORGET_PASSWORD = "forgetpwd";
    const TYPE_REGIST = "regist";

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'email_check_codes';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[
            'member_id'],
                'required'],
            [[
            'member_id',
            'created_at'],
                'integer'],
            [[
            'email'],
                'string',
                'max' => 255],
            [[
            'check_code'],
                'string',
                'max' => 60],
            [[
            'type'],
                'string',
                'max' => 20],
            [[
            'other'],
                'string',
                'max' => 20],
            [[
            'member_id'],
                'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'         => "ID",
            'member_id'  => '會員編號',
            'check_code' => '驗證碼',
            'email'      => 'Email',
            'type'       => '類型',
            'other'      => '其他',
            'created_at' => '建立時間',
        ];
    }

}
