<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblBillHeadReleaseApplicability;

/**
 * TblBillHeadReleaseApplicabilitySearch represents the model behind the search form about `app\modules\vsp\models\TblBillHeadReleaseApplicability`.
 */
class TblBillHeadReleaseApplicabilitySearch extends TblBillHeadReleaseApplicability {

    public $mcc_name, $code_ex;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bill_head_release_applicabilty_code', 'payment_cycle_code', 'is_processed', 'is_disbursed', 'originating_type'], 'integer'],
                [['bill_head_code', 'bill_head_for', 'applicable_code', 'applicable_for', 'bmc_code', 'from_date', 'to_date', 'disburse_date', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['previous_amount', 'current_amount', 'total_amount'], 'number'],
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
        $query = TblBillHeadReleaseApplicability::find();
        $query->where(['tbl_bill_head_release_applicability.bill_head_code' => $this->bill_head_code]);
        $query->orderBy(['tbl_bill_head_release_applicability.from_date' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['mainCustomerCode', 'dcsCode', 'bmcCode', 'mccPlantCode', 'plantCode', 'customerType']);


        if (!empty($this->from_date))
            $query->andFilterWhere(['cast(tbl_bill_head_release_applicability.from_date as date)' => date('Y-m-d', strtotime($this->from_date))]);
        // grid filtering conditions

        $query->andFilterWhere(['or',
                ['like', 'tbl_dcs.dcs_name', $this->mcc_name],
                ['like', 'tbl_customer_master.customer_name', $this->mcc_name],
                ['like', 'tbl_plant.name', $this->mcc_name],
                ['like', 'tbl_mcc_plant.name', $this->mcc_name],
                ['like', 'tbl_bmc.bmc_name', $this->mcc_name]
        ]);

        $query->andFilterWhere(['or',
                ['like', 'tbl_dcs.dcs_code_ex', $this->code_ex],
                ['like', 'tbl_customer_master.customer_code_ex', $this->code_ex],
        ]);

        $query->andFilterWhere(['like', 'tbl_bill_head_release_applicability.applicable_code', $this->applicable_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->applicable_for])
                ->andFilterWhere(['like', 'tbl_bill_head_release_applicability.union_code', $this->union_code]);


        return $dataProvider;
    }

}
