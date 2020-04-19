<?php
Yii::$app->params["hideAlert"] = true;
$this->registerJs("top.location.reload();",  yii\web\View::POS_HEAD);
?>