<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div>
    <h3>編輯會員資料</h3>
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
            "required" => true,
        ]);

        ?>
        <span>點選姓名即可編輯/更改暱稱</span>
    </div>
    <div class="email">
        <?=
        Html::activeInput("email", $model, "email", [
            'placeholder' => "請輸入信箱",
            'disabled'    => true,
            "required"    => true,
        ]);

        ?>
        <span>會員信箱不可變更</span>
    </div>
    <?php if ($model->social_type == "email"): ?>
        <div class="psw">
            <?=
            Html::activeInput("password", $model, "password", [
                'placeholder' => "若無更新請留空",
            ]);

            ?>
            <span>更改密碼</span>
        </div>
        <div class="psw">
            <?=
            Html::activeInput("password", $model, "chkpassword", [
                'placeholder' => "若無更新請留空",
            ]);

            ?>
            <span>確認更改密碼</span>
        </div>
    <?php endif; ?>
    <button type="submit">完成</button>
    <?php ActiveForm::end(); ?>
</div>