<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblVspBillHeadCriteriaApplicability;

/**
 * TblVspBillHeadCriteriaApplicabilitySearch represents the model behind the search form about `app\modules\vsp\models\TblVspBillHeadCriteriaApplicability`.
 */
class TblVspBillHeadCriteriaApplicabilitySearch extends TblVspBillHeadCriteriaApplicability {

    public $code_ex, $ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bill_head_criteria_applicability_code', 'originating_type'], 'integer'],
            [['vsp_criteria_code', 'wef_date', 'bill_head_code', 'union_code', 'applicable_code', 'applicable_for', 'bmc_code', 'bill_head_for', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['ref_code', 'code_ex'], 'safe'],
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
        $query = TblVspBillHeadCriteriaApplicability::find();

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
        $query->joinWith(['dcsCode', 'mainCustomerCode.bmcCode']);

        // grid filtering conditions
        $query->andFilterWhere([
            'bill_head_criteria_applicability_code' => $this->bill_head_criteria_applicability_code,
            'wef_date' => $this->wef_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);


        $query->andFilterWhere(['or',
            ['like', 'tbl_dcs.dcs_code_ex', $this->code_ex],
            ['like', 'tbl_customer_master.customer_code_ex', $this->code_ex],
        ]);

        $query->andFilterWhere(['or',
            ['like', 'tbl_dcs.ref_code', $this->ref_code],
            ['like', 'tbl_customer_master.ref_code', $this->ref_code],
        ]);

        $query->andFilterWhere(['like', 'vsp_criteria_code', $this->vsp_criteria_code])
                ->andFilterWhere(['like', 'bill_head_code', $this->bill_head_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'applicable_code', $this->applicable_code])
                ->andFilterWhere(['like', 'applicable_for', $this->applicable_for])
                ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'bill_head_for', $this->bill_head_for])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

}
