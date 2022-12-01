<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeCriteria;

/**
 * TblSchemeCriteriaSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeCriteria`.
 */
class TblSchemeCriteriaSearch extends TblSchemeCriteria
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scheme_criteria_id', 'scheme_id', 'originating_type'], 'integer'],
            [['wef_date', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['min_pouring_day', 'min_pouring_qty', 'scheme_value'], 'number'],
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
        $query = TblSchemeCriteria::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'scheme_criteria_id' => $this->scheme_criteria_id,
            'scheme_id' => $this->scheme_id,
            'wef_date' => $this->wef_date,
            'min_pouring_day' => $this->min_pouring_day,
            'min_pouring_qty' => $this->min_pouring_qty,
            'scheme_value' => $this->scheme_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
