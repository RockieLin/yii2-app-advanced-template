<?php

use kartik\widgets\ActiveForm;

?>

<div class="admin-form row">
    <?php
    $form = ActiveForm::begin([
                'enableClientScript' => true,
                'options'            => [
                    'enctype' => 'multipart/form-data']]);

    ?>
    <?=
    $form->errorSummary([
        $model]);

    ?>
    <div class="col-xs-12 col-md-12">
        <?=
        $form->field($model, 'name')->textInput([
            'placeholder' => "請輸入角色名稱",
        ]);

        ?>
    </div>

    <div class="col-xs-12 col-md-12 center">
        <div class="form-group">
            <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;確定</button>
            <a class="btn btn-default" onclick="closeModal()"><i class="fa fa-times"></i>&nbsp;&nbsp;返回</a>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
