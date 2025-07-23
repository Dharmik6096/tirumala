<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblSchemeRateApplicability;

/**
 * TblSchemeRateApplicabilitySearch represents the model behind the search form about `app\modules\dcsoperation\models\TblSchemeRateApplicability`.
 */
class TblSchemeRateApplicabilitySearch extends TblSchemeRateApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['scheme_rate_app_code', 'from_shift', 'to_shift'], 'integer'],
                [['scheme_rate_code', 'is_member_rate', 'from_date', 'to_date', 'applicable_for', 'applicable_code', 'union_code', 'rate_class', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'description'], 'safe'],
                [['rtpl'], 'number'],
        ];
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
        $query = TblSchemeRateApplicability::find();

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
            'scheme_rate_app_code' => $this->scheme_rate_app_code,
            'from_date' => $this->from_date,
            'from_shift' => $this->from_shift,
            'to_date' => $this->to_date,
            'to_shift' => $this->to_shift,
            'rtpl' => $this->rtpl,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_active' => $this->is_active,
            'scheme_rate_code' => $this->scheme_rate_code,
        ]);

        $query->andFilterWhere(['like', 'applicable_for', $this->applicable_for])
                ->andFilterWhere(['like', 'applicable_code', $this->applicable_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'rate_class', $this->rate_class])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }

}
