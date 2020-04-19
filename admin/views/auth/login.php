<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;

common\assets\Theme\ModernBlueAsset::register($this);
$this->beginPage();

$title = Yii::$app->controller->title;
$logo = "/images/logo.jpeg";

?>
<!DOCTYPE html>
<html class="paceSimple app footer-sticky">
<head>
    <?= Html::csrfMetaTags() ?>
    <title><?= $title; ?></title>
    <?php $this->head() ?>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
</head>
<body class=" loginWrapper menu-right-hidden">
<?php $this->beginBody() ?>
<!-- Main Container Fluid -->
<div class="container-fluid menu-hidden">
    <!-- Content -->
    <div id="content" style="padding-bottom: 20px;">
        <div class="layout-app">

            <!-- row-app -->
            <div class="row row-app">
                <!-- col -->
                <!-- col-separator.box -->
                <div class="col-separator col-unscrollable box">
                    <!-- col-table -->
                    <div class="col-table">
                        <!-- col-table-row -->
                        <div class="col-table-row">
                            <!-- col-app -->
                            <div class="col-app col-unscrollable">
                                <!-- col-app -->
                                <div class="col-app">
                                    <div class="login">
                                        <div class="text-center">
                                            <img class="" src="<?=$logo;?>"
                                                 style="width: 150px;margin: 90px 0 20px 0;border: 0px;"/>
                                            <h4 class="innerAll margin-none text-center">
                                                <?= $title; ?>
                                            </h4>
                                        </div>
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <div class="panel-body">
                                                <?php
                                                $form = ActiveForm::begin([
                                                    'id' => 'form',
                                                    "validateOnSubmit" => true,
                                                    'enableClientValidation' => true,
                                                    'options' => [
                                                        'enctype' => 'multipart/form-data'
                                                    ],
                                                ]);

                                                ?>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">帳號</label>
                                                    <?=
                                                    $form->field($model, 'username', [
                                                        'template' => "{input}\n{error}",
                                                    ])->input('email', [
                                                        'placeholder' => "帳號",
                                                        'class' => 'form-control',
                                                        'required' => 'required',
                                                    ]);

                                                    ?>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">密碼</label>
                                                    <?=
                                                    $form->field($model, 'password', [
                                                        'template' => "{input}\n{error}",
                                                    ])->passwordInput([
                                                        'placeholder' => "密碼",
                                                        'class' => 'form-control',
                                                    ]);

                                                    ?>
                                                </div>

                                                <button type="submit" class="btn btn-primary btn-block">登入</button>
                                                <?php ActiveForm::end(); ?>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <!-- // END col-app -->

                            </div>
                            <!-- // END col-app.col-unscrollable -->

                        </div>
                        <!-- // END col-table-row -->
                    </div>
                    <!-- // END col-table -->
                </div>
                <!-- // END col-separator.box -->
            </div>
            <!-- // END row-app -->
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
