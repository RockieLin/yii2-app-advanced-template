<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->registerCss(".layout-app .col-separator{background-color: transparent;}");

?>
<div class="admin-index">
    <?php \yii\widgets\Pjax::begin([
        'timeout'         => false,
        'enablePushState' => true,
    ]); ?>
    <div class="widget search">
        <div class="widget-body row">
            <div class="col-xs-12 col-md-12">
                <p>
                    <?= $this->render('_search', ['model' => $searchModel]); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="widget">
        <div class="widget-body row">
            <div class="col-xs-12 col-md-12">
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options' => ["class" => 'table-responsive table-primary'],
                    'tableOptions' => [
                        'class' => 'table table-striped',
                    ],
                    'columns' => [
                        array(
                            'attribute' => "id",
                            "contentOptions" => array(
                                "style" => "width:70px;",
                            ),
                        ),
                        'username',
                        'name',
                        [
                            'format' => 'raw',
                            'attribute' => 'status',
                            'value' => function ($data) {
                                if ($data->status == 1) {
                                    $cls = "btn-info";
                                } else {
                                    $cls = "btn-default";
                                }
                                $type = Yii::$app->params["statusList"][$data->status];

                                return Html::a($type, [
                                    'status',
                                    'id' => $data->id
                                ], [
                                    'title' => $type,
                                    'class' => "btn btn-sm {$cls}",
                                    'data' => [
                                        'confirm' => "確定變更狀態?",
                                        'method' => 'post',
                                    ]
                                ]);
                            },
                        ],
                        array(
                            'format' => 'raw',
                            'attribute' => 'role',
                            'value' => function ($data) {
                                return Yii::$app->params["_tmpRoles"][$data->role];
                            },
                        ),
                        array(
                            'attribute' => "created_at",
                            'format' => 'raw',
                            'value' => function ($data) {
                                return date("Y/m/d H:i", $data->created_at);
                            }
                        ),
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            //'headerOptions' => ['class' => 'grid-operate-col'],
                            'template' => '{update}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="fa fa-edit fa-fw"></i> 編輯', [
                                        'update',
                                        'id' => $model->id
                                    ], [
                                        'title' => '編輯',
                                        'class' => 'btn btn-sm btn-primary',
                                        'data-pjax' => "0",
                                    ]);
                                },
                                'status' => function ($url, $model) {
                                    if ($model->status == 1) {
                                        $type = "停用";
                                        $class = "btn-info";
                                    } else {
                                        $type = "啟用";
                                        $class = "btn-warning";
                                    }
                                    return Html::a('<i class="fa fa-unlock-alt fa-fw"></i> ' . $type, [
                                        'status',
                                        'id' => $model->id
                                    ], [
                                        'title' => $type,
                                        'class' => 'btn btn-sm ' . $class,
                                        'data' => [
                                            'confirm' => "確定{$type}這筆資料?",
                                            'method' => 'post',
                                        ]
                                    ]);
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a('<i class="fa fa-trash-o fa-fw"></i> 刪除', [
                                        'delete',
                                        'id' => $model->id
                                    ], [
                                        'title' => '刪除',
                                        'class' => 'btn btn-sm btn-danger',
                                        'data' => [
                                            'confirm' => '確定刪除這筆資料?',
                                            'method' => 'post',
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
    <?php \yii\widgets\Pjax::end(); ?>
</div>
