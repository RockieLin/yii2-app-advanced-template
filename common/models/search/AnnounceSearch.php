<?php namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\entities\Announce;

/**
 * AnnounceSearch represents the model behind the search form about `common\models\entities\Announce`.
 */
class AnnounceSearch extends Announce {
    public $isAvailable;

    public $query_start;
    public $query_end;
    public $query_time_range;

    public function init() {
        //避掉parent loadDefaultValues
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [
                [
                    'id',
                    'start_time',
                    'end_time',
                    'status',
                    'updated_at',
                    'created_at'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'content',
                    'image',
                    'keyword',
                    'query_time_range',
                    'query_start',
                    'query_end',
                ],
                'safe'
            ],
        ];
    }

    public function attributeLabels() {
        $labels = [
            "query_start" => "開始時間",
            "query_end" => "結束時間",
            "query_time_range" => "時間區間",
        ];
        return array_merge(parent::attributeLabels(), $labels);
    }

    /**
     * @inheritdoc
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
        $query = Announce::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'defaultPageSize' => Yii::$app->params['defaultPageSize'],
                'pageParam' => 'page',
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $query->where("status != -1");

        $query->andFilterWhere([
            'id' => $this->id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere([
            'like',
            'title',
            $this->title
        ])
            ->andFilterWhere([
                'like',
                'content',
                $this->content
            ])
            ->andFilterWhere([
                'like',
                'image',
                $this->image
            ])
            ->andFilterWhere([
                'like',
                'keyword',
                $this->keyword
            ]);


        if ($this->query_start) {
            $query->andWhere("start_time >= :starttime")
                ->addParams([":starttime" => strtotime($this->query_start)]);
        } else {
            $this->query_start = null;
        }
        if ($this->query_end) {
            $query->andWhere("end_time <= :endtime")
                ->addParams([":endtime" => strtotime($this->query_end)]);
        } else {
            $this->query_end = null;
        }

        if ($this->isAvailable) {
            $query->andWhere("start_time <= :time AND end_time >= :time")
                ->addParams([":time" => time()]);
        }

        return $dataProvider;
    }

}
