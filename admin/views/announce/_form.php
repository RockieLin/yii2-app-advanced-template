<?php

use yii\helpers\Url;
use kartik\widgets\ActiveForm;

$this->registerJs("imagePreview();");
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
    <div class="col-md-3">
        <div class="widget row widget-inverse">
            <div class="widget-head search_collapse">
                <h4 class="heading strong text-uppercase btn-primary">
                    <label>輪播設定</label>
                </h4>
            </div>
            <div class="widget-body">
                <div class="row">
                    <div class="col-md-12">
                        <?=
                        $form->field($model, 'title')->input('text', [
                            'class' => 'form-control',
                            'required' => 'required'
                        ]);
                        ?>
                    </div>
                    <div class="col-md-12">
                        <?=
                        $form->field($model, 'time_range')->widget(kartik\daterange\DateRangePicker::classname(), [
                            'options' => [
                                'class' => 'form-control',
                                'placeholder' => '請選擇時間區間...'
                            ],
                            'useWithAddon' => true,
                            'convertFormat' => true,
                            'hideInput' => true,
                            'presetDropdown' => true,
                            'startAttribute' => 'start_time',
                            'endAttribute' => 'end_time',
                            'pluginOptions' => [
                                'timePicker' => true,
                                'timePickerIncrement' => 5,
                                'locale' => [
                                    'format' => 'Y-m-d H:i'
                                ],
                            ],
                        ]);

                        ?>
                    </div>
                    <div class="col-md-12">
                        <?=
                        $this->render("_image", [
                            "form" => $form,
                            "model" => $model,
                            "size_type" => "announce",
                            "name" => "image",

                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="widget row widget-inverse">
            <div class="widget-head search_collapse">
                <h4 class="heading strong text-uppercase btn-primary">
                    <label>公告內容</label>
                </h4>
            </div>
            <div class="widget-body">
                <div class="row">
                    <div class="col-md-12">
                        <?=
                        $form->field($model, 'content')->widget(vova07\imperavi\Widget::className(), [
                            'settings' => [
                                'lang' => 'zh_tw',
                                'minHeight' => 500,
                                'pastePlainText' => true,
                                'imageManagerJson' => Url::to(['/announce/images-get']),
                                'imageUpload' => Url::to(['/announce/image-upload']),
                                'plugins' => [
                                    'fontsize',
                                    'fontcolor',
                                    'table',
                                    'textdirection',
                                    'imagemanager',
                                ]
                            ]
                        ])->label(false);

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



