<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblDispatchCenterApplicability;

/**
 * TblDispatchCenterApplicabilitySearch represents the model behind the search form about `app\modules\product\models\TblDispatchCenterApplicability`.
 */
class TblDispatchCenterApplicabilitySearch extends TblDispatchCenterApplicability {

    public $code_ex;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dispatch_center_applicability_code', 'dispatch_center_code', 'dispatch_center_name', 'applicable_code', 'applicable_for', 'applicable_type', 'union_code', 'mcc_plant_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['originating_type','code_ex'], 'safe'],
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
        $query = TblDispatchCenterApplicability::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode']);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'dispatch_center_code' => $this->dispatch_center_code,
        ]);

        $query->andFilterWhere(['like', 'tbl_dispatch_center_applicability.dispatch_center_applicability_code', $this->dispatch_center_applicability_code])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.dispatch_center_name', $this->dispatch_center_name])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.applicable_code', $this->applicable_code])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.applicable_for', $this->applicable_for])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.applicable_type', $this->applicable_type])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code_ex', $this->code_ex])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.created_by', $this->created_by])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'tbl_dispatch_center_applicability.x_col5', $this->x_col5]);

        return $dataProvider;
    }

}
