<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

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
        Html::activeInput("email", $model, "email", [
            'placeholder' => "請輸入信箱",
            'autofocus'   => 'autofocus',
            "required"    => true,
        ]);

        ?>
    </div>
    <button type="submit" class="confirm">確認信箱</button>
    <?php ActiveForm::end(); ?>

</div>