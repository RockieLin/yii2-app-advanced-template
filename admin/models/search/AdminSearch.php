<?php namespace admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\entities\Admin;

/**
 * AdminSearch represents the model behind the search form about `admin\models\entities\Admin`.
 */
class AdminSearch extends Admin {

    public $keyword;

    public function init() {
        //避掉parent loadDefaultValues
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [[
            'id',
            'status',
            'role',
            'updated_at',
            'created_at'],
                'integer'],
            [[
            'username',
            'password',
            'name',
            'keyword'],
                'safe'],
        ];
    }

    public function attributeLabels() {
        $labels = [
            "keyword" => "關鍵字",
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
        $query = Admin::find();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'defaultPageSize' => Yii::$app->params['defaultPageSize'],
                'pageParam'       => 'page',
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
            'id'         => $this->id,
            'status'     => $this->status,
            'role'       => $this->role,
            'updated_at'    => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like',
                    'username',
                    $this->username])
                ->andFilterWhere(['like',
                    'password',
                    $this->password])
                ->andFilterWhere(['like',
                    'name',
                    $this->name]);

        if ($this->keyword) {
            $query->andWhere("username LIKE :keyword OR name LIKE :keyword")
                    ->addParams([":keyword" => "%{$this->keyword}%"]);
        }

        return $dataProvider;
    }

}
