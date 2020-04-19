<?php namespace front\controllers;

use Yii;
use common\models\entities\Member;
use common\models\entities\EmailCheckCodes;

class UserController extends BaseController {

    public function init() {
        parent::init();
        $this->layout = "main";
        $this->title = "會員";
    }

    /**
     * fb登入用 沒用到可移除
     */
    public function actions() {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [
                    $this,
                    'socialSuccessCallback'
                ],
                'SuccessUrl' => Yii::$app->user->returnUrl
            ],
        ];
    }

    public function behaviors() {

        $_userAction = [
            'index',
            'update',
            'logout',
        ];

        return \yii\helpers\ArrayHelper::merge([

            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => $_userAction,
                'rules' => [
                    [
                        'actions' => $_userAction,
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ], parent::behaviors());
    }

    public function actionGologin() {
        $this->layout = false;
        return $this->render('gologin');
    }

    public function actionLogin() {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \front\models\forms\LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function socialSuccessCallback($client) {
        $attributes = $client->getUserAttributes();
        //print_r($attributes);exit;
        $serviceName = strtolower($client->getId());
        $_info = Yii::$app->socialLogin->getAttributes($serviceName, $attributes);

        $username = "{$_info["id"]}@{$serviceName}.id";
        $password = "{$_info["id"]}_{$serviceName}_email";

        //登入成功，查詢是否已有帳號
        $session = Yii::$app->session;
        unset($session['tmpUser']);
        $userModel = Member::findByUsername($username);
        if (!$userModel) {
            //建新的
            $userModel = new Member;
            //寫入
            $userModel->attributes = array(
                "username" => $username,
                "password" => $password,
                "name" => $_info["name"],
                "nickname" => $_info["name"],
                "social_type" => $serviceName,
                "email" => @$_info["email"],
                "status" => Member::STATUS_EMAIL_VALIDATE,
            );

            $session['tmpUser'] = $userModel;
            return $this->redirect(["user/fb-register"]);
        }

        //登入
        return $this->_userLogin($username, $password);
    }

    public function actionIndex() {
        $_user = Yii::$app->user;

        $model = $this->loadModel($_user->getId());
        if ($model->social_type == "email") {
            $model->scenario = "updatepwd";
        }

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->password) {
                $model->password = $model->getOldAttribute("password");
                $model->scenario = "default";
            }


            if ($model->getOldAttribute("password") != $model->password) {
                if (!$model->validate("password")) {
                    return $this->render('index', array(
                        'model' => $model,
                    ));
                }

                //有更新密碼
                $model->password = md5($model->password);
                $model->chkpassword = md5($model->chkpassword);
            }

            if ($model->validate() && $model->save()) {
                //更新session狀態
                $_user->setIdentity($model);

                Yii::$app->getSession()->setFlash('alert', "資料更新完成");
                return $this->refresh();
            }
        }

        $model->password = $model->chkpassword = null;
        return $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionRegister() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->getSession()->setFlash('alert', "登入完成");
            return $this->redirect(["/"]);
        }

        $model = new Member(["scenario" => "insert"]);

        if ($model->load(\Yii::$app->request->post())) {
            $model->status = Member::STATUS_EMAIL_VALIDATE;
            $email = $model->username;
            $model->email = $email;
            $model->social_type = "email";

            if ($model->save()) {
                //發mail
//                $result = Yii::$app->tool->sendUserEmailCheckCode($model, EmailCheckCodes::TYPE_REGIST);
//
//                if (!$result) {
//                    throw new \yii\web\HttpException(400, "Email發送失敗");
//                }
//                $maskMail = Yii::$app->tool->mask_email($model->username);
//
//                Yii::$app->getSession()->setFlash('alert', "我們將發送一封Email到您的信箱 {$maskMail}, 請依照信中指示進行驗證");

                return $this->goHome();
            }
        }

        $model->password = null;
        $model->chkpassword = null;
        return $this->render('register', array(
            'model' => $model,
        ));
    }

    public function actionFbRegister() {
        Yii::$app->params["tmpFb"] = true;

        $session = Yii::$app->session;

        if (!isset($session['tmpUser'])) {
            throw new \yii\web\HttpException(400, "無法從Facebook獲取您相關的資料");
        }

        $model = $session['tmpUser'];
        $model->scenario = "fb-insert";
        Member::deleteAll([
            "social_type" => $session['tmpUser']->social_type,
            "username" => $session['tmpUser']->username,
            "status" => 1
        ]);

        if ($model->load(\Yii::$app->request->post())) {
            $model->attributes = array(
                "username" => $session['tmpUser']->username,
                "password" => $session['tmpUser']->password,
                "chkpassword" => $session['tmpUser']->password,
                "name" => $session['tmpUser']->name,
                "social_type" => $session['tmpUser']->social_type,
                "status" => Member::STATUS_EMAIL_VALIDATE,
            );
            if ($model->validate() && $model->save()) {
                //發mail
//                $result = Yii::$app->tool->sendUserEmailCheckCode($model, EmailCheckCodes::TYPE_REGIST);
//
//                if (!$result) {
//                    throw new \yii\web\HttpException(400, Yii::$app->tool->formatErrorMsg($model->getErrors()));
//                }
                $maskMail = Yii::$app->tool->mask_email($model->email);

                Yii::$app->getSession()->setFlash('alert', "我們將發送一封Email到您的信箱 {$maskMail}, 請依照信中指示進行驗證");
                unset($session['tmpUser']);
                return $this->goHome();
            }
        }

        return $this->render('fbRegister', array(
            'model' => $model,
        ));
    }

    public function actionRegistEmailConfirm($checkCode) {
        if (isset($checkCode) && !empty($checkCode)) {
            $emailModel = EmailCheckCodes::findOne(array(
                "type" => EmailCheckCodes::TYPE_REGIST,
                "check_code" => $checkCode
            ));
            if (!$emailModel) {
                throw new \yii\web\HttpException(400, "驗證碼錯誤");
            } else {
                $member_id = $emailModel->member_id;

                $model = Member::findOne(array(
                    'id' => $member_id
                ));
                if (!$model) {
                    throw new \yii\web\HttpException(400, "查無對應帳號");
                }
                $model->setAttributes(array(
                    'status' => 2,
                ));

                if (!$model->save()) {
                    Yii::$app->tool->formatErrorMsg($model->getErrors());
                } else {
                    EmailCheckCodes::deleteAll("member_id = '{$emailModel->member_id}'");
                }
            }
        } else {
            throw new \yii\web\HttpException(400, null);
        }
        $this->layout = false;
        Yii::$app->getSession()->setFlash('alert', "您的帳號已認證完成，請進行登入");
        return $this->redirect(["user/login"]);
    }

    public function actionResendRegistMail() {
        $this->subTitle = "重寄Email認證信";

        $model = new \front\models\forms\ResendRegistMailForm;
        $model->type = EmailCheckCodes::TYPE_REGIST;

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->checkRule()) {
            if ($model->user->status == 2) {
                Yii::$app->getSession()->setFlash('alert', "您的帳號已啟用, 請直接登入");
                return $this->redirect(["login"]);
            }
            //發mail
            $result = Yii::$app->tool->sendUserEmailCheckCode($model->user, EmailCheckCodes::TYPE_REGIST);

            if (!$result) {
                throw new \yii\web\HttpException(400, "Email發送失敗");
            }

            $maskMail = Yii::$app->tool->mask_email($model->username);
            Yii::$app->getSession()->setFlash('alert', "我們已發送一封Email到您的信箱 {$maskMail}, 請依照信中指示進行驗證");

            return $this->redirect("/");
            //return $this->refresh();
        }

        return $this->render('resendRegistMail', array(
            "model" => $model
        ));
    }

    public function actionForgetPwd() {
        $this->title = "查詢密碼";

        $model = new \front\models\forms\ResendRegistMailForm;
        $model->type = EmailCheckCodes::TYPE_FORGET_PASSWORD;

        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->checkRule()) {
            $password = Yii::$app->tool->generatorRandomString();
            //發mail
            $result = Yii::$app->tool->sendUserEmailCheckCode($model->user, EmailCheckCodes::TYPE_FORGET_PASSWORD, $password);

            if (!$result) {
                throw new \yii\web\HttpException(400, null);
            }

            $maskMail = Yii::$app->tool->mask_email($model->username);
            Yii::$app->getSession()->setFlash('alert', "我們將發送一封Email到您的信箱 {$maskMail}, 請依照信中指示進行變更密碼");

            return $this->redirect("/");
        }

        return $this->render('forgetPwd', array(
            "model" => $model
        ));
    }

    public function actionForgetPwdConfirm($checkCode) {
        if (isset($checkCode) && !empty($checkCode)) {
            $emailModel = EmailCheckCodes::findOne(array(
                "type" => EmailCheckCodes::TYPE_FORGET_PASSWORD,
                "check_code" => $checkCode
            ));
            if (!$emailModel) {
                throw new \yii\web\HttpException(400, "認證碼錯誤");
            } else {
                $model = Member::findOne(array(
                    'id' => $emailModel->member_id
                ));
                if (!$model) {
                    throw new \yii\web\HttpException(400, "查無對應帳號");
                }

                $model->password = md5($emailModel->other);
                if (!$model->save()) {
                    throw new \yii\web\HttpException(400, Yii::$app->tool->formatErrorMsg($model->getErrors()));
                } else {
                    EmailCheckCodes::deleteAll("member_id = '{$emailModel->member_id}'");
                }
            }
        } else {
            throw new \yii\web\HttpException(400, "認證碼錯誤");
        }
        $this->layout = false;

        Yii::$app->getSession()->setFlash('alert', "您的密碼已更新, 請使用新密碼進行登入");
        return $this->redirect(["user/login"]);
    }

    private function _userLogin($username, $password, $redirect = true) {
        //帳號狀態驗證
        $model = new \front\models\forms\LoginForm();
        $model->username = $username;
        $model->password = $password;

        if (!$model->login()) {
            if ($model->_user->status != Member::STATUS_EMAIL_VALIDATE && $model->_user->social_type != "email") {
                //email沒認證
                return $this->redirect(["fb-register"]);
            } else {
                Yii::$app->getSession()->setFlash('alert', Yii::$app->tool->formatErrorMsg($model->getErrors()));
                return $this->redirect(["register"]);
            }
        }

        return $this->goHome();
    }

    private function loadModel($id) {
        $model = Member::findOne(["id" => $id]);
        if (!$model) {
            throw new \yii\web\HttpException(404, "查無此帳號");
        }

        return $model;
    }

}
