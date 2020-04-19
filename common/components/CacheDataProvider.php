<?php namespace common\components;

use yii\db\QueryInterface;
use yii\base\InvalidConfigException;
use Yii;

class CacheDataProvider extends \yii\data\ActiveDataProvider {

    public $dependency = null;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function prepareModels() {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
        }

        $query = clone $this->query;
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();
            $query->limit($pagination->getLimit())->offset($pagination->getOffset());
        }

        if (($sort = $this->getSort()) !== false) {
            $query->addOrderBy($sort->getOrders());
        }

        return Yii::$app->db->cache(function ($db) use ($query) {
                    return $query->all($db);
                }, Yii::$app->db->queryCacheDuration, $this->dependency);
    }

}

?>