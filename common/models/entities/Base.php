<?php namespace common\models\entities;

use yii\db\ActiveRecord;

class Base extends ActiveRecord {

    public $param;
    public $param1;
    public $param2;
    public $param3;
    public $param4;
    public $resultObj = ["result"  => true,
        "code"    => 0,
        "message" => null];

    public function init() {
        parent::init();
        $this->loadDefaultValues();
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            if ($this->hasAttribute("created_at")) {
                $this->created_at = time();
            }
        }

        if ($this->hasAttribute("updated_at")) {
            $this->updated_at = time();
        }

        return parent::beforeSave($insert);
    }

    public static function getPostfix() {
        $_lang = \Yii::$app->language;
        if ($_lang == "zh-CN") {
            $postfix = "cn";
        } else {
            $postfix = "tw";
        }
        return $postfix;
    }

}
