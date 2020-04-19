<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->registerCss(".layout-app .col-separator{background-color: transparent;}");

?>
<div class="admin-index">
    <?php \yii\widgets\Pjax::begin(); ?>
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
                <p>
                    <?= Html::a('新增', ['create'], ['class' => 'btn btn-primary'])

                    ?>
                </p>

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
                        'title',
                        array(
                            'attribute' => "image",
                            'format' => 'raw',
                            'value' => function ($data) {
                                $result = '<a target="_blank" href="' . $data->getImage() . '"><img src="' . $data->getImage() . '" class="img-r100-40"></a>';

                                return $result;
                            }
                        ),
                        array(
                            'attribute' => "start_time",
                            'format' => 'raw',
                            'value' => function ($data) {
                                return date("Y/m/d H:i", $data->start_time);
                            }
                        ),
                        array(
                            'attribute' => "end_time",
                            'format' => 'raw',
                            'value' => function ($data) {
                                return date("Y/m/d H:i", $data->end_time);
                            }
                        ),
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            //'headerOptions' => ['class' => 'grid-operate-col'],
                            'template' => '{update}{delete}',
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
