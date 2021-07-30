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

    public $code_ex, $ref_code, $name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['penalty_rate_applicability_code', 'penalty_rate_code', 'penalty_type', 'wef_date', 'applicable_code', 'applicable_for', 'applicable_type', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'code_ex', 'ref_code', 'name'], 'safe'],
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
        $query->joinWith(['mainCustomerCode', 'dcsCode', 'customerTypeFor']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        if (!empty($this->wef_date)) {
            $query->andwhere(['wef_date' => date('Y-m-d', strtotime($this->wef_date))]);
        }
        $query->andFilterWhere(['or',
            ['like', 'tbl_dcs.dcs_code_ex', $this->code_ex],
            ['like', 'tbl_customer_master.customer_code_ex', $this->code_ex]
        ]);
        $query->andFilterWhere(['or',
            ['like', 'tbl_dcs.ref_code', $this->ref_code],
            ['like', 'tbl_customer_master.ref_code', $this->ref_code]
        ]);
        $query->andFilterWhere(['or',
            ['like', 'tbl_dcs.dcs_name', $this->name],
            ['like', 'tbl_customer_master.customer_name', $this->name]
        ]);
        $query->andFilterWhere(['like', 'penalty_rate_applicability_code', $this->penalty_rate_applicability_code])
                ->andFilterWhere(['like', 'penalty_rate_code', $this->penalty_rate_code])
                ->andFilterWhere(['like', 'penalty_type', $this->penalty_type])
                ->andFilterWhere(['like', 'applicable_code', $this->applicable_code])
                ->andFilterWhere(['like', 'penalty_rate', $this->penalty_rate])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->applicable_for])
                ->andFilterWhere(['like', 'applicable_type', $this->applicable_type])
                ->andFilterWhere(['like', 'tbl_collection_penalty_rate_applicability.bmc_code', $this->bmc_code])
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
