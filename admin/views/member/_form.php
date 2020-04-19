<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\SwitchInput;

?>

<div class="admin-form">
    <?php
    $form = ActiveForm::begin(['enableClientScript' => false,
                'options'            => ['enctype' => 'multipart/form-data']]);

    ?>
    <?=
    $form->errorSummary([$model]);

    ?>
    <input name="dontautofill" style="display: none;" type="password" />
    <div class="col-xs-12 col-md-6">
        <?=
        $form->field($model, 'username')->input('email', [
            'class'       => 'form-control',
            'required'    => 'required',
            'placeholder' => 'Email格式',
            'disabled'    => $model->isNewRecord ? false : true,
        ]);

        ?>
    </div>
    <div class="col-xs-12 col-md-6">
        <?=
        $form->field($model, 'password')->passwordInput(['maxlength'   => true,
            'placeholder' => $model->isNewRecord ? null : "若無更新請留空"])

        ?>
    </div>
    <div class="col-xs-12 col-md-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-xs-12 col-md-6">
        <?=
        $form->field($model, 'status')->widget(SwitchInput::classname(), [
            'type' => SwitchInput::CHECKBOX,
        ]);

        ?> 
    </div>

    <div class="col-xs-12 col-md-12 center">
        <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;確認送出</button>
            <a class="btn btn-default" href="<?= Yii::$app->tool->toBaseUrl(["index"]); ?>"><i class="fa fa-times"></i>&nbsp;&nbsp;返回</a>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
