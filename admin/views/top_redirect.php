<?php
Yii::$app->params["hideAlert"] = true;
$this->registerJs("top.location='{$url}';", yii\web\View::POS_HEAD);

?>