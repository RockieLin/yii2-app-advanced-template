<div class="admin-create">

    <h3><?= Yii::$app->controller->title . " 編輯 - " . $model->title; ?></h3>
    <?=
    $this->render('_form', [
        'model' => $model,
    ])

    ?>

</div>
