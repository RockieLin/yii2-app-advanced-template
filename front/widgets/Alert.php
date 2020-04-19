<?php

namespace front\widgets;

use Yii;

class Alert extends \yii\base\Widget {


    public function init() {
        parent::init();

        if (Yii::$app->session->hasFlash('alert')) {
            $msg = Yii::$app->session->getFlash('alert');
            $msg = str_replace("'", "\'", $msg);
            $this->getView()->registerJs("alert('{$msg}')");
        }
    }

}
