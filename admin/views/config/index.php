<?php

use common\models\entities\CarType;
use common\models\entities\Vendor;
use yii\grid\GridView;
use yii\helpers\Html;

?>
<div class="admin-index">
    <div class="widget search">
        <ul class="nav nav-tabs">
            <?php foreach (\common\models\entities\Config::CATEGORY_LIST as $_key => $_val): ?>
                <li class="<?= $searchModel->category == $_key ? "active" : null; ?>">
                    <a href="<?= Yii::$app->tool->toCurrent([
                        "category" => $_key
                    ]) ?>"><?= $_val; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
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
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options'      => [
                        "class" => 'table-primary'
                    ],
                    'tableOptions' => [
                        'class' => 'table table-striped table-bordered',
                    ],
                    'columns'      => [
                        [
                            'attribute'      => "key",
                            "contentOptions" => [
                                "style" => "width:200px;",
                            ],
                        ],
                        'name',
                        [
                            'attribute'      => "content",
                            'format'         => 'raw',
                            "contentOptions" => [],
                            'value'          => function ($data) {
                                if ($data->type == 'image') {
                                    $img = $data->getImage('content');
                                    return "<a target='_blank' href='{$img}'><img class='img-s100' src='{$img}'/> </a>";
                                } else {
                                    return "<div style='max-width:50vw;overflow:auto;white-space: nowrap;'>" . strip_tags($data->content) . "</div>";
                                }
                            }
                        ],
                        [
                            'attribute' => "updated_at",
                            'format'    => 'raw',
                            'value'     => function ($data) {
                                return date("Y/m/d H:i", $data->updated_at);
                            }
                        ],
                        [
                            'header'        => "操作",
                            'headerOptions' => [
                                "style" => "width: 210px;"
                            ],
                            'class'         => 'yii\grid\ActionColumn',
                            'template'      => '{update}',
                            'buttons'       => [
                                'update' => function ($url, $model) {
                                    return Html::a('<i class="fa fa-edit fa-fw"></i> 修改', [
                                        '#'
                                    ], [
                                        'title'     => "修改",
                                        'class'     => 'btn btn-sm btn-primary auto-modal',
                                        'data-href' => Yii::$app->tool->toBaseUrl([
                                            "update",
                                            "id" => $model["id"]
                                        ]),
                                    ]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
