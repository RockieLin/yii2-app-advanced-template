<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'id'     => 'searchForm'
        ]);

?>
<div class="col-md-3 col-xs-6">
    <?= $form->field($model, 'keyword')->textInput(['placeholder' => '輸入帳號或暱稱']);

    ?>
</div>
<div class="col-md-2 col-xs-6">
    <?= $form->field($model, 'role')->dropDownList(Yii::$app->params["_tmpRoles"], ["class"       => "form-control",
        'prompt'      => '全部']);

    ?>
</div>
<div class="col-md-2 col-xs-6">
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params["statusList"], ["class"       => "form-control",
        'prompt'      => '全部']);

    ?>
</div>
<div class="col-md-5 col-xs-12">
    <div class="form-group" style="line-height:80px;">
        <?=
        Html::submitButton("搜尋", [
            'class' => 'btn btn-primary'])

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
