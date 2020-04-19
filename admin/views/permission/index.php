<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->registerJs("updateRoleAssign();");

?>

<div class="admin-index">

    <?php \yii\widgets\Pjax::begin(); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'       => '{items}',
        'options'      => ["class" => 'table-responsive table-primary'],
        'columns'      => $columns,
    ]);

    ?>
<?php \yii\widgets\Pjax::end(); ?>
</div>
