<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMemberPaymentAlias;

/**
 * TblMemberPaymentAliasSearch represents the model behind the search form about `app\modules\payment\models\TblMemberPaymentAlias`.
 */
class TblMemberPaymentAliasSearch extends TblMemberPaymentAlias {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_payment_alias_code', 'payment_cycle_code', 'payment_cycle_applicabilty_code', 'is_verified', 'originating_type'], 'integer'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'adjust_remark', 'disburse_date', 'payment_date', 'payment_status', 'approved_by', 'transfer_mode', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'vsp_payment_reference_no', 'utr_no', 'reference_no', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'from_datetime', 'to_datetime'], 'safe'],
                [['qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'additional_pay', 'total_addition', 'previous_hold', 'previous_due', 'hold_amount', 'net_payable'], 'number'],
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
        $query = TblMemberPaymentAlias::find();

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
            'payment_cycle_code' => $this->payment_cycle_code,
            'bmc_code' => $this->bmc_code,
            'dcs_code' => $this->dcs_code,
        ]);
        return $dataProvider;
    }

}
