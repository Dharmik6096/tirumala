<?php

namespace app\modules\changelog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\changelog\models\TblChangeLog;

/**
 * TblChangeLogSearch represents the model behind the search form about `app\modules\changelog\models\TblChangeLog`.
 */
class TblChangeLogSearch extends TblChangeLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_code', 'is_active'], 'integer'],
            [['description', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblChangeLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['created_at'=> SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'log_code' => $this->log_code,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
