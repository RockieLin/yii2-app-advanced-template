<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->registerCss(".layout-app .col-separator{background-color: transparent;}");

?>
<div class="admin-index">
    <div class="widget">
        <div class="widget-body row">
            <div class="col-xs-12 col-md-12">
                <p>
                    <?=
                    Html::a('新增', [
                        '#'], [
                        'data-href' => Yii::$app->tool->toBaseUrl([
                            "admin/role-create"]),
                        'class'     => 'btn btn-success auto-modal'])

                    ?>
                </p>

                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options'      => [
                        "class" => 'table-responsive table-primary'],
                    'tableOptions' => [
                        'class' => 'table table-striped',
                    ],
                    'columns'      => [
//                        array(
//                            'attribute'      => "id",
//                            'header'         => 'ID',
//                            "contentOptions" => array(
//                                "style" => "width:70px;",
//                            ),
//                        ),
                        array(
                            'header'    => '角色名稱',
                            'attribute' => 'name',
                        ),
                        array(
                            'header'    => '最後修改時間',
                            'format'    => 'raw',
                            'attribute' => 'updated_at',
                            'value'     => function($data) {
                                return date("Y/m/d H:i:s", $data["updated_at"]);
                            },
                        ),
                        [
                            'header'   => "操作",
                            'class'    => 'yii\grid\ActionColumn',
                            //'headerOptions' => ['class' => 'grid-operate-col'],
                            'template' => '{update}{delete}',
                            'buttons'  => [
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="fa fa-edit fa-fw"></i> 編輯', [
                                                '#'], [
                                                'title'     => '編輯',
                                                'class'     => 'btn btn-sm btn-primary auto-modal',
                                                'data-href' => Yii::$app->tool->toBaseUrl([
                                                    "admin/role-update",
                                                    "id" => $model["id"]]),
                                    ]);
                                },
                                'delete'   => function ($url, $model) {
                                    return Html::a('<i class="fa fa-trash fa-fw"></i> 刪除', [
                                                'role-delete',
                                                'id' => $model["id"]], [
                                                'title' => '刪除',
                                                'class' => 'btn btn-sm btn-danger',
                                                'data'  => [
                                                    'confirm' => '確定刪除這筆資料? 所有套用此角色的帳號需重新設定權限!',
                                                    'method'  => 'post',
                                                ]
                                    ]);
                                },
                            ],
                        ],
                    ],
                ]);

                ?>
            </div>
        </div>
    </div>
</div>
