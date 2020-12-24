<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblCollectionPenaltyRateApplicability;

/**
 * TblCollectionPenaltyRateApplicabilitySearch represents the model behind the search form about `app\modules\collection\models\TblCollectionPenaltyRateApplicability`.
 */
class TblCollectionPenaltyRateApplicabilitySearch extends TblCollectionPenaltyRateApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['penalty_rate_applicability_code', 'penalty_rate_code', 'penalty_type', 'wef_date', 'applicable_code', 'applicable_for', 'applicable_type', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['penalty_rate'], 'number'],
            [['originating_type'], 'integer'],
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
        $query = TblCollectionPenaltyRateApplicability::find();

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
            'penalty_rate' => $this->penalty_rate,
            'wef_date' => $this->wef_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'penalty_rate_applicability_code', $this->penalty_rate_applicability_code])
                ->andFilterWhere(['like', 'penalty_rate_code', $this->penalty_rate_code])
                ->andFilterWhere(['like', 'penalty_type', $this->penalty_type])
                ->andFilterWhere(['like', 'applicable_code', $this->applicable_code])
                ->andFilterWhere(['like', 'applicable_for', $this->applicable_for])
                ->andFilterWhere(['like', 'applicable_type', $this->applicable_type])
                ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
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
