<?php

use yii\widgets\ActiveForm;

?>
<?php
$form = ActiveForm::begin([
            "validateOnSubmit"       => true,
            'enableClientValidation' => true,
            'options'                => ['enctype' => 'multipart/form-data']
        ]);

?>
<?=
        $form->field($model, 'username', [
            'template' => '{label}{input}{error}',
        ])->input('email', [
            'placeholder' => "請輸入" . $model->getAttributeLabel("username"),
            'class'       => 'form-control',
            'required'    => 'required',
        ])
        ->label($model->getAttributeLabel("username"), ["class" => "modal_leave"]);

?>

<button type="submit" class="btn btn-share btn_submit btn-red-cave btn-red"><?= Yii::t("app", "submit"); ?></button>
<?php ActiveForm::end(); ?>