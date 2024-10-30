<?php

use vova07\imperavi\Widget;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="admin-form form-low row">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]);

    ?>
    <?= $form->errorSummary([
        $model
    ]);

    ?>
    <div class="col-md-12">
        <?= $form->field($model, 'type')->dropDownList(\common\models\entities\Config::TYPE_LIST, [
            'disabled' => $model->isNewRecord ? false : true,
            'onchange' => 'insertParamToUrl("type",this.value)',
        ]);

        ?>
    </div>
    <div class="col-xs-12 col-md-6">
        <?= $form->field($model, 'key')->textInput([
            'disabled' => $model->isNewRecord ? false : true,
        ]);

        ?>
    </div>
    <div class="col-xs-12 col-md-6">
        <?= $form->field($model, 'name')->textInput([]);

        ?>
    </div>
    <div class="col-xs-12 col-md-12">
        <?php if ($model->type == 'image'): ?>
            <div class="col-md-offset-3 col-md-6">
                <div class="col-md-offset-3 col-md-6">
                    <?= $this->render("@app/views/image_input", [
                        "form"       => $form,
                        "model"      => $model,
                        "name"       => "content",
                        "size_type"  => "common",
                        "show_label" => true,
                    ]); ?>
                </div>
            </div>
        <?php elseif ($model->type == 'editor'): ?>
            <?= $form->field($model, "content")->widget(Widget::className(), [
                'settings' => [
                    'lang'             => 'en',
                    'minHeight'        => 450,
                    'pastePlainText'   => true,
                    'imageManagerJson' => Url::to([
                        'images-get'
                    ]),
                    'imageUpload'      => Url::to([
                        'image-upload'
                    ]),
                    'plugins'          => [
                        'fontsize',
                        'fontcolor',
                        'table',
                        'textdirection',
                        'imagemanager',
                    ]
                ]
            ])->label(false); ?>
        <?php else: ?>
            <?= $form->field($model, 'content')->textarea([
                "rows" => 5
            ])

            ?>
        <?php endif; ?>
    </div>
    <div class="col-xs-12 col-md-12">
        <?= $form->field($model, 'description')->textarea([
            "rows" => 3
        ])

        ?>
    </div>

    <div class="col-xs-12 col-md-12 center">
        <div class="form-group">
            <button type="submit" class="btn btn-success"><i
                        class="fa fa-check-circle"></i>&nbsp;&nbsp;確認
            </button>
            <a class="btn btn-default" onclick="closeModal()"><i
                        class="fa fa-times"></i>&nbsp;&nbsp;返回</a>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
