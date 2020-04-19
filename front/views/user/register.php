<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="inner">
    <?php
    $form = ActiveForm::begin([
                'id'                     => 'form',
                "validateOnSubmit"       => true,
                "validateOnChange"       => false,
                "validateOnBlur"         => false,
                'enableClientValidation' => true,
                "errorSummaryCssClass"   => "error",
                'options'                => [
                    'enctype' => 'multipart/form-data'],
    ]);

    ?>
    <?= $form->errorSummary($model); ?>
    <div class="name">
        <?=
        Html::activeInput("text", $model, "name", [
            'placeholder' => "請輸入姓名",
            'autofocus'   => 'autofocus',
            "required"    => true,
        ]);

        ?>
    </div>
   
    <div class="email">
        <?=
        Html::activeInput("email", $model, "username", [
            'placeholder' => "請輸入信箱",
            "required"    => true,
        ]);

        ?>
    </div>
    <div class="psw">
        <?=
        Html::activeInput("password", $model, "password", [
            'placeholder' => "請輸入密碼",
            "required"    => true,
        ]);

        ?>
        <span>輸入6-12位元英數密碼（不分大小寫）</span>
    </div>
    <div class="psw">
        <?=
        Html::activeInput("password", $model, "chkpassword", [
            'placeholder' => "請確認密碼",
            "required"    => true,
        ]);

        ?>
        <span>確認您的密碼</span>
    </div>


    <div class="reload">
        <div class="image">
            <?=
            $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className(), [
                'template'     => '<div class="pic">{image}</div>',
                "imageOptions" => ["style" => "width:60px;height:40px;"]
            ])->label(false)->error(false);

            ?>
        </div>
        <div class="code">
            <?=
            Html::activeInput("text", $model, "captcha", [
                'placeholder' => "請輸入圖形驗證碼",
                "required"    => true,
                "style"       => "padding-left: 70px; padding-right: 0px;"
            ]);

            ?>
        </div>
        <span>請輸入圖形驗證碼</span>
    </div>
    <p>如果繼續，即表示同意接受會員條款</p>
    <button type="submit">註冊</button>
    <?php ActiveForm::end(); ?>
</div>
<footer>
    <img src="/image/ui/btn_facebook_blue.png" alt="facebook">
    <h3><a href="/user/auth?authclient=facebook">使用Facebook登入</a></h3>
</footer>