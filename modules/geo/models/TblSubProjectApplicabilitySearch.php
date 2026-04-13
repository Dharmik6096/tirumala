<?php

namespace app\modules\geo\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\geo\models\TblSubProjectApplicability;

/**
 * TblSubProjectApplicabilitySearch represents the model behind the search form about `app\modules\geo\models\TblSubProjectApplicability`.
 */
class TblSubProjectApplicabilitySearch extends TblSubProjectApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sub_project_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'applicable_for', 'applicable_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = TblSubProjectApplicability::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode', 'bmcCode']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['tbl_sub_project_applicability.sub_project_code' => $this->sub_project_code]);
        if (!empty($this->wef_date))
            $query->andFilterWhere(['like', 'wef_date', date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['like', 'applicable_for', $this->applicable_for])
                ->andFilterWhere(['like', 'tbl_bmc.ref_code', $this->bmc_code])
                ->andFilterWhere(['like', 'applicable_code', $this->applicable_code]);

        return $dataProvider;
    }

}
