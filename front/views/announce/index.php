<?php

use nirvana\infinitescroll\InfiniteScrollPager;

?>

<?=
yii\widgets\ListView::widget([
    "id"           => "listview",
    'dataProvider' => $dataProvider,
    'itemView'     => '_announce',
    'layout'       => '<ul class="announce_content">{items}{pager}</ul>',
    'options'      => [
        "class" => "content"
    ],
    'itemOptions'  => [
        'tag' => false,
    ],
    'pager'        => [
        'class'                 => InfiniteScrollPager::className(),
        'widgetId'              => "listview",
        'itemsCssClass'         => 'announce_content',
        'contentLoadedCallback' => 'null',
        'nextPageLabel'         => null,
        'linkOptions'           => [],
        'pluginOptions'         => [
            "loading" => [
                "finishedMsg" => "已經沒有資料囉",
                "msgText"     => "讀取中...",
            ],
        ],
    ],
]);

?>

