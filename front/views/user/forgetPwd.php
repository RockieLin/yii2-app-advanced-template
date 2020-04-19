<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<div class="inner">
    <p style="margin-bottom: 20px;">輸入E-mail並按下確認鍵，系統將寄送新密碼到您的信箱，請在30分鐘內至信箱領取，逾期該密碼將失效</p>
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
    <button>送出</button>
    <?php ActiveForm::end(); ?>
</div>
