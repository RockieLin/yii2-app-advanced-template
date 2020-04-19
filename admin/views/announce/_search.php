<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'id' => 'searchForm'
]);

?>
<div class="col-xs-12 col-md-2">
    <?= $form->field($model, 'keyword')->textInput(['placeholder' => '請輸入關鍵字']);

    ?>
</div>
<div class="col-md-3 col-xs-12">
    <?=
    $form->field($model, 'query_time_range')->widget(kartik\daterange\DateRangePicker::classname(), [
        'options' => [
            'class' => 'form-control',
            'placeholder' => '請選擇時間區間...'
        ],
        'useWithAddon' => true,
        'convertFormat' => true,
        'hideInput' => true,
//        'presetDropdown' => true,
        'startAttribute' => 'query_start',
        'endAttribute' => 'query_end',
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
<div class="col-md-4 col-xs-12">
    <div class="form-group" style="line-height:80px;">
        <?=
        Html::submitButton("搜尋", [
            'class' => 'btn btn-primary'
        ])
        ?>
        <?=
        Html::a('新增', [
            'create'
        ], [
            'class' => 'btn btn-success'
        ])

        ?>
    </div>
</div>


<?php ActiveForm::end(); ?>         

