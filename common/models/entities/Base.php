<?php namespace common\models\entities;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\HtmlPurifier;
use yii\web\UploadedFile;

class Base extends ActiveRecord {
    const dbName = "shop";

    public $param;
    public $param1;
    public $param2;

    public function __construct($config = []) {
        parent::__construct($config);
    }

    public function init() {
        parent::init();

        //讀取db預設值
        if (static::tableName() != "{{%base}}") {
            $this->loadDefaultValues();
        }
    }

    public function beforeSave($insert) {
        //預設新增、修改時間
        $current_time = time();
        if ($this->isNewRecord && $this->created_at == null) {
            if ($this->hasAttribute("created_at")) {
                $this->created_at = $current_time;
            }
        }

        if ($this->hasAttribute("updated_at")) {
            $this->updated_at = $current_time;
        }

        $attrs = array_keys($this->getAttributes());
        foreach ($attrs as $attr) {
            if (strpos($attr, "translation") !== false || strpos($attr, "html") !== false) {
                continue;
            }
            if (!empty($this->$attr) && is_string($this->$attr)) {
                $this->$attr = strip_tags($this->$attr);
            }
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        //清緩存
        $this->clearCache();
    }

    public function afterDelete() {
        parent::afterDelete();
        //清緩存
        $key = get_called_class() . "_getCache_{$this->id}";
        Yii::$app->cache->delete($key);

        //清緩存
        $this->clearCache();
    }

    public function clearCache() {
        //清緩存
        $key = get_called_class() . "_getCache_{$this->id}";
        unset(Yii::$app->params[$key]);
        Yii::$app->cache->delete($key);
    }

    public static function getCache($id) {
        $key = get_called_class() . "_getCache_{$id}";
        if (isset(Yii::$app->params[$key])) {
            return Yii::$app->params[$key];
        }

        $data = Yii::$app->cache->get($key);
        if ($data === false) {
            $data = self::findOne(["id" => $id]);
            Yii::$app->cache->set($key, $data);
        }

        Yii::$app->params[$key] = $data;

        return $data;
    }

    //更新重取 SQL產生FOR UPDATE 用於transaction lock
    public function getForUpdate() {
        $data = self::find()->where("id = :id FOR UPDATE")->addParams([":id" => $this->id])->one();

        //更新緩存
        $key = get_called_class() . "_getCache_{$this->id}";
        Yii::$app->cache->set($key, $data);
        Yii::$app->params[$key] = $data;

        return $data;
    }

    public function getImage($attribute = "image") {
        if (empty($this->$attribute)) {
            $this->$attribute = "/images/none.jpg";
        }
        return Yii::$app->params["staticFileUrl"] . $this->$attribute;
    }

    /**
     * 儲存圖檔 不轉檔縮圖
     */
    public function saveRawImage($field, $image_type, $toWebp = false) {
        $uploadPath = Yii::getAlias('@common') . "/web";
        $_image = UploadedFile::getInstance($this, $field);

        if (strpos($field, "]")) {
            $field = substr($field, strpos($field, "]") + 1);
        }
        if ($_image) {
            //            echo $field;exit;
            $imageFileName = "/uploads/{$image_type}/" . uniqid() . '.';
            $input = $imageFileName . $_image->extension;

            $_image->saveAs($uploadPath . $input);

            if (in_array($_image->extension, [
                'gif',
                'svg',
                'webp'
            ])) {
                $toWebp = false;
            }
            if ($toWebp && Yii::$app->tool->webpImage($uploadPath . $input)) {
                $this->$field = str_replace("." . $_image->extension, ".webp", $input);
            } else {
                $this->$field = $input;
            }
        } else {
            $tmp = $this->getOldAttribute($field);
            if (!empty($tmp)) {
                $this->$field = $tmp;
            }
        }
        if (empty($this->$field)) {
            $this->$field = "/images/none.jpg";
        }
    }

    /**
     * 儲存多圖檔
     */
    public function saveMultipleImage($field, $image_type, $toWebp = false) {
        $files = UploadedFile::getInstances($this, $field);

        $lists = [];
        if (!empty($files)) {
            $uploadPath = Yii::getAlias('@common') . "/web";
            foreach ($files as $key => $_image) {
                $_toWebp = $toWebp;
                $fileName = "/uploads/{$image_type}/" . uniqid() . '.' . $_image->extension;
                $_image->saveAs($uploadPath . $fileName);
                if (in_array($_image->extension, [
                    'gif',
                    'svg',
                    'webp'
                ])) {
                    $_toWebp = false;
                }
                if ($_toWebp && Yii::$app->tool->webpImage($uploadPath . $fileName, 90)) {
                    $fileName = str_replace("." . $_image->extension, ".webp", $fileName);
                }

                array_push($lists, $fileName);
            }

            $lists = array_filter($lists);
        }
        //舊的資料保留
        $keepOld = Yii::$app->request->post((new \ReflectionClass(get_called_class()))->getShortName());
        $keepOld = isset($keepOld[$field]) ? $keepOld[$field] : [];
        $oldList = $this->getOldAttribute($field);
        if (empty($oldList)) {
            $oldList = [];
        } else {
            $oldList = json_decode($oldList);
            $oldList = array_filter($oldList, function ($v) use ($keepOld) {
                return in_array($v, $keepOld);
            }, ARRAY_FILTER_USE_KEY);
        }
        uksort($oldList, function ($a, $b) use ($keepOld) {
            $posA = array_search($a, $keepOld);
            $posB = array_search($b, $keepOld);
            return $posA - $posB;
        });
        //        print_r($keepOld);
        //        print_r($oldList);exit;

        $this->$field = array_merge($oldList, $lists);
    }
}
