<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMemberPayment;

/**
 * TblMemberPaymentSearch represents the model behind the search form about `app\modules\payment\models\TblMemberPayment`.
 */
class TblMemberPaymentSearch extends TblMemberPayment {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_payment_code', 'payment_cycle_code', 'payment_cycle_applicabilty_code', 'ack', 'is_verified'], 'integer'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'adjust_remark', 'disburse_date', 'payment_date', 'payment_status', 'approved_by', 'transfer_mode', 'error_code', 'error_log', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'vsp_payment_reference_no', 'utr_no', 'reference_no', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'addition', 'previous_hold', 'previous_due', 'hold_amount', 'net_payable', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
                [['qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'adjust_amount'], 'number'],
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
        $query = TblMemberPayment::find();

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
            'member_payment_code' => $this->member_payment_code,
            'payment_cycle_code' => $this->payment_cycle_code,
            'payment_cycle_applicabilty_code' => $this->payment_cycle_applicabilty_code,
            'qty' => $this->qty,
            'avg_fat' => $this->avg_fat,
            'avg_snf' => $this->avg_snf,
            'kg_fat' => $this->kg_fat,
            'kg_snf' => $this->kg_snf,
            'avg_rate' => $this->avg_rate,
            'total_amount' => $this->total_amount,
            'total_deduction' => $this->total_deduction,
            'final_amount' => $this->final_amount,
            'disburse_amount' => $this->disburse_amount,
            'adjust_amount' => $this->adjust_amount,
            'disburse_date' => $this->disburse_date,
            'payment_date' => $this->payment_date,
            'ack' => $this->ack,
            'is_verified' => $this->is_verified,
            'process_date' => $this->process_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'member_code', $this->member_code])
                ->andFilterWhere(['like', 'adjust_remark', $this->adjust_remark])
                ->andFilterWhere(['like', 'payment_status', $this->payment_status])
                ->andFilterWhere(['like', 'approved_by', $this->approved_by])
                ->andFilterWhere(['like', 'transfer_mode', $this->transfer_mode])
                ->andFilterWhere(['like', 'error_code', $this->error_code])
                ->andFilterWhere(['like', 'error_log', $this->error_log])
                ->andFilterWhere(['like', 'bank_name', $this->bank_name])
                ->andFilterWhere(['like', 'bank_code', $this->bank_code])
                ->andFilterWhere(['like', 'branch_name', $this->branch_name])
                ->andFilterWhere(['like', 'branch_code', $this->branch_code])
                ->andFilterWhere(['like', 'ifsc', $this->ifsc])
                ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
                ->andFilterWhere(['like', 'vsp_payment_reference_no', $this->vsp_payment_reference_no])
                ->andFilterWhere(['like', 'utr_no', $this->utr_no])
                ->andFilterWhere(['like', 'reference_no', $this->reference_no])
                ->andFilterWhere(['like', 'reject_reason', $this->reject_reason])
                ->andFilterWhere(['like', 'bank_status', $this->bank_status])
                ->andFilterWhere(['like', 'payment_transaction_code', $this->payment_transaction_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

}
