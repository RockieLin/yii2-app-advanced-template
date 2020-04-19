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
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'options'      => ["class" => 'table-responsive table-primary'],
                    'tableOptions' => [
                        'class' => 'table table-striped',
                    ],
                    'columns'      => [
                        array(
                            'attribute'      => "id",
                            "contentOptions" => array(
                                "style" => "width:70px;",
                            ),
                        ),
                        'username',
                        'email',
                        'name',
                        array(
                            'format'    => 'raw',
                            'attribute' => 'status',
                            'value'     => function($data) {
                                return Yii::$app->params["memberStatus"][$data->status];
                            },
                        ),
                        //"barcode",
                        array(
                            'attribute' => "created_at",
                            'format'    => 'raw',
                            'value'     => function($data) {
                                return date("Y/m/d H:i", $data->created_at);
                            }
                        ),
                        [
                            'header'   => "操作",
                            'class'    => 'yii\grid\ActionColumn',
                            //'headerOptions' => ['class' => 'grid-operate-col'],
                            'template' => '{update}{status}',
                            'buttons'  => [
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
                                    if ($model->status != 0) {
                                        $type = "停用";
                                        $confirm = "是否確定停用該用戶? 若選擇停用請自行發送通知信件給該用戶．";
                                        $btn = "btn-danger";
                                    } else {
                                        $type = "啟用";
                                        $confirm = "是否確定啟用該用戶?";
                                        $btn = "btn-info";
                                    }
                                    return Html::a('<i class="fa fa-unlock-alt fa-fw"></i> ' . $type, ['status',
                                                'id' => $model->id], [
                                                'title' => $type,
                                                'class' => 'btn btn-sm ' . $btn,
                                                'data'  => [
                                                    'confirm' => $confirm,
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
            <?php \yii\widgets\Pjax::end(); ?>
</div>
