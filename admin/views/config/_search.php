<?php

use common\models\entities\CarType;
use common\models\entities\Vendor;
use kartik\form\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'action'  => [
        'index',
        'category' => $model->category
    ],
    'method'  => 'get',
    'id'      => 'searchForm',
    'options' => [
        "autocomplete" => "off"
    ]
]);
?>
<div class="col-xs-6 col-md-2">
    <?= $form->field($model, 'name')->textInput([]); ?>
</div>
<div class="col-md-5 col-xs-12">
    <div class="form-group" style="line-height:80px;">
        <?= Html::submitButton("搜尋", ['class' => 'btn btn-primary']) ?>
        <?= Html::a('新增', [
            '#'
        ], [
            'data-href' => Yii::$app->tool->toBaseUrl([
                "create",
                'category' => $model->category
            ]),
            'class'     => 'btn btn-success auto-modal'
        ])

        ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
