<?php

namespace common\models\search;

use common\models\entities\Config;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SettingSearch represents the model behind the search form of `common\models\entities\Setting`.
 */
class ConfigSearch extends Config {
    public function init() {
        //避掉parent loadDefaultValues
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
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
                    'key',
                    'name',
                    'content'
                ],
                'safe'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Config::find();

        $this->load($params);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => [
                'defaultOrder' => [
                    'key' => SORT_ASC,
                ]
            ],
            'pagination' => [
                'defaultPageSize' => Yii::$app->params['defaultPageSize'],
                'pageParam'       => 'page',
            ],
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'         => $this->id,
            'key'        => $this->key,
            'category'   => $this->category,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere([
            'like',
            'name',
            $this->name
        ])->andFilterWhere([
                'like',
                'content',
                $this->content
            ]);

        return $dataProvider;
    }

}
