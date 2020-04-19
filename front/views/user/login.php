<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div>
    <a href="/user/auth?authclient=facebook">Facebook登入</a>
</div>
<div>
    <a href="<?= Yii::$app->tool->toBaseUrl(["user/forget-pwd"]); ?>">查詢帳號密碼</a>
</div>
<div>
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
    <div class="email">
        <?=
        Html::activeInput("email", $model, "username", [
            'placeholder' => "請輸入信箱",
            'autofocus'   => 'autofocus',
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
    </div>
    <button type="submit" class="login">登入</button>
    <?php ActiveForm::end(); ?>
</div>
