<?php

namespace common\models\entities;

use common\models\entities\Base;
use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property int $id ID
 * @property string $name 項目
 * @property string $content 數值
 * @property int $updated_at 最後修改
 * @property int $created_at 建立時間
 * @property string $key [varchar(50)]  key
 * @property string $description 描述
 * @property string $category
 * @property int $sorting
 */
class Config extends Base {

    const     CONFIG_TYPE = [
        "text"   => "文字",
        "editor" => "編輯器",
        "image"  => "圖片",
    ];

    public static function tableName() {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [
                [
                    'key',
                    'name',
                ],
                'required'
            ],
            [
                [
                    'id',
                    'updated_at',
                    'created_at',
                    'sorting'
                ],
                'integer'
            ],
            [
                [
                    'key'
                ],
                'string',
                'max' => 50
            ],
            [
                [
                    'category'
                ],
                'string',
                'max' => 10
            ],
            [
                [
                    'name'
                ],
                'string',
                'max' => 255
            ],
            [
                ['description'],
                'string'
            ],
            [
                [
                    'content'
                ],
                'safe',
            ],
            [
                [
                    'content'
                ],
                'safe',
                'on' => ['text', 'editor'],
            ],
            [
                [
                    'content'
                ],
                'file',
                'on'         => 'image',
                'extensions' => 'jpg,jpeg,gif,png,svg',
                'maxSize'    => 4 * 1024 * 1024
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id'          => 'ID',
            'sorting'     => '排序',
            'category'    => '類型',
            'key'         => 'key',
            'name'        => '名稱',
            'content'     => '內容',
            'type'        => '資料類型',
            'description' => '描述',
            'updated_at'  => '最後修改',
            'created_at'  => '建立時間',
        ];
    }

    public function clearCache() {
        parent::clearCache();
        //清緩存
        $key = __CLASS__ . "_getCacheByKey_{$this->key}";
        Yii::$app->cache->delete($key);
    }

    public static function getCacheByKey($key) {
        $cacheKey = __CLASS__ . "_getCacheByKey_{$key}";

        if (isset(Yii::$app->params[$cacheKey])) {
            return Yii::$app->params[$cacheKey];
        }

        $data = Yii::$app->cache->get($cacheKey);
        if ($data === false) {
            $data = self::findOne(["key" => $key]);
            Yii::$app->params[$cacheKey] = $data;
            Yii::$app->cache->set($cacheKey, $data);
        }
        return $data;
    }

    public static function getEnum($key) {
        $result = self::getCacheByKey($key);
        return json_decode($result->content, true);
    }

    public function getImage($attribute = "content") {
        return parent::getImage('content');
    }
}
