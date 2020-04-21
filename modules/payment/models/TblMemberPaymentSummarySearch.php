<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMemberPaymentSummary;

/**
 * TblMemberPaymentSummarySearch represents the model behind the search form about `app\modules\payment\models\TblMemberPaymentSummary`.
 */
class TblMemberPaymentSummarySearch extends TblMemberPaymentSummary {

    public $from_date, $to_date, $dcs_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_sumary_code', 'member_count', 'payment_cycle_code', 'payment_cycle_applicabilty_code', 'ack'], 'integer'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'disburse_date', 'payment_date', 'payment_status', 'error_code', 'error_log', 'created_at', 'created_by', 'updated_at', 'updated_by', 'from_date', 'to_date', 'dcs_name', 'addition', 'previous_hold', 'previous_due', 'hold_amount', 'net_payable', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
                [['qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount'], 'number'],
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
        $query = TblMemberPaymentSummary::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'paymentCycleCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_member_payment_summary', 'tbl_member_payment_summary', 'tbl_member_payment_summary');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'member_count' => $this->member_count,
            'tbl_member_payment_summary.payment_cycle_code' => $this->payment_cycle_code,
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
            'disburse_date' => $this->disburse_date,
            'payment_date' => $this->payment_date,
        ]);

        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', 'tbl_member_payment_summary.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_member_payment_summary.mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'tbl_member_payment_summary.bmc_code', $this->bmc_code]);

        return $dataProvider;
    }

}
