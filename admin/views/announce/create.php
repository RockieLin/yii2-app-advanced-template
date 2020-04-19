<div class="admin-create">

    <h3><?= Yii::$app->controller->title . " 新增"; ?></h3>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])

    ?>

</div>
