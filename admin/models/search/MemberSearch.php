<?php namespace admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\entities\Member;

/**
 * MemberSearch represents the model behind the search form about `common\models\entities\Member`.
 */
class MemberSearch extends Member {

    public $query_start;
    public $query_end;
    public $query_time_range;
    public $keyword;

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
                    'status',
                    'updated_at',
                    'created_at'
                ],
                'integer'
            ],
            [
                [
                    'query_time_range',
                    'social_type',
                    'username',
                    'password',
                    'email',
                    'name',
                    'query_start',
                    'query_end',
                    'keyword'
                ],
                'safe'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function attributeLabels() {
        $labels = [
            "keyword" => "關鍵字",
            "query_start" => "註冊時間",
            "query_end" => "註冊時間",
            "query_time_range" => "註冊時間",
        ];
        return array_merge(parent::attributeLabels(), $labels);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Member::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $query->where("1 = 1");

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere([
            'like',
            'social_type',
            $this->social_type
        ])
            ->andFilterWhere([
                'like',
                'username',
                $this->username
            ])
            ->andFilterWhere([
                'like',
                'password',
                $this->password
            ])
            ->andFilterWhere([
                'like',
                'email',
                $this->email
            ])
            ->andFilterWhere([
                'like',
                'name',
                $this->name
            ]);

        if ($this->query_start) {
            $query->andWhere("created_at >= :starttime")
                ->addParams([":starttime" => strtotime($this->query_start)]);
        } else {
            $this->query_start = null;
        }
        if ($this->query_end) {
            $query->andWhere("created_at <= :endtime")
                ->addParams([":endtime" => strtotime($this->query_end)]);
        } else {
            $this->query_end = null;
        }

        if ($this->keyword) {
            $query->andWhere("id LIKE :keyword OR username LIKE :keyword OR name LIKE :keyword OR email LIKE :keyword")
                ->addParams([":keyword" => "%{$this->keyword}%"]);
        }

        return $dataProvider;
    }

}
