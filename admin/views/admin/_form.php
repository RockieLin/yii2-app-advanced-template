<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\SwitchInput;

?>

<div class="admin-form">
    <?php
    $form = ActiveForm::begin([
        'enableClientScript' => false,
        'options' => ['enctype' => 'multipart/form-data']
    ]);

    ?>
    <?=
    $form->errorSummary([$model]);

    ?>
    <div class="col-md-6">
        <div class="widget row widget-inverse">
            <div class="widget-head search_collapse">
                <h4 class="heading strong text-uppercase btn-primary">
                    <label>基本設定</label>
                </h4>
            </div>
            <div class="widget">
                <div class="row">
                    <div class="col-md-12">
                        <input name="dontautofill" style="display: none;" type="password"/>
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-12">
                        <?=
                        $form->field($model, 'username')->input('email', [
                            'class' => 'form-control',
                            'required' => 'required',
                            'placeholder' => 'Email格式',
                            'disabled' => $model->isNewRecord ? false : true,
                        ]);

                        ?>
                    </div>
                    <div class="col-md-12">
                        <?=
                        $form->field($model, 'password')->passwordInput([
                            'maxlength' => true,
                            'placeholder' => $model->isNewRecord ? null : "若無更新請留空"
                        ]);
                        ?>

                    </div>
                    <div class="col-md-12">
                        <?=
                        $form->field($model, 'status')->widget(SwitchInput::classname(), [
                            'type' => SwitchInput::CHECKBOX,
                        ]);

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="widget row widget-inverse">
            <div class="widget-head search_collapse">
                <h4 class="heading strong text-uppercase btn-primary">
                    <label>權限設定</label>
                </h4>
            </div>
            <div class="widget-body">
                <div class="row">
                    <div class="col-md-12">
                        <?=
                        $form->field($model, 'role')->dropDownList(\common\models\entities\Admin::getRoles(), ['class' => 'form-control']);

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <hr class="col-md-12"/>

    <div class="col-xs-12 col-md-12 center">
        <div class="form-group">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i>&nbsp;&nbsp;確定</button>
            <a class="btn btn-default" href="<?= Yii::$app->tool->toBaseUrl(["index"]); ?>"><i class="fa fa-times"></i>&nbsp;&nbsp;返回</a>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
