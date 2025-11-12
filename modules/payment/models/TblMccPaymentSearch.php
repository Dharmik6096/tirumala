<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMccPayment;

/**
 * TblMccPaymentSearch represents the model behind the search form about `app\modules\payment\models\TblMccPayment`.
 */
class TblMccPaymentSearch extends TblMccPayment {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['mcc_payment_code', 'payment_cycle_code', 'payment_cycle_applicabilty_code', 'is_verified', 'originating_type', 'from_shift', 'to_shift', 'member_billing_lock_check'], 'integer'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'customer_type', 'customer_code', 'adjust_remark', 'created_at', 'created_by', 'updated_at', 'updated_by', 'status', 'disburse_date', 'payment_date', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'utr_no', 'reference_no', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code', 'originating_org_code', 'originating_org_type', 'from_datetime', 'to_datetime', 'billing_type', 'billing_based_on', 'route_code'], 'safe'],
                [['kg_fat', 'kg_snf', 'total_qty', 'total_loss', 'amount', 'addition', 'deduction', 'net_payable', 'adjust_amount', 'final_pay', 'disburse_amount', 'previous_hold', 'previous_due', 'hold_amount', 'commission_param1', 'commission_param2', 'deviation_penalty', 'deviation_max_cap', 'adjust_recovery', 'recovery', 'avg_fat', 'avg_snf', 'std_qty'], 'number'],
                [['pin_code', 'aadhaar_no', 'contact_person_name', 'mobile_no', 'beneficiary_name', ' pan_no', 'gst_no', 'bmc_collection_amount', 'state_code', 'sub_district_code', 'village_code', 'hamlet_code', 'district_code', 'minimum_qty', 'minimum_qty_amount', 'total_qty_amount', 'transfered_qty', 'billing_qty', 'received_qty', 'bmc_collection_qty', 'received_amount', 'transfered_amount', 'billing_qty_amount', 'from_date', 'to_date'], 'safe'],
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
        $query = TblMccPayment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['bmcCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_payment', 'tbl_mcc_payment', 'tbl_mcc_payment');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->from_date) && !empty($this->to_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andWhere('((\'' . $from_date . '\'  between tbl_mcc_payment.from_datetime and tbl_mcc_payment.to_datetime) OR (\'' . $to_date . '\' between tbl_mcc_payment.from_datetime  and tbl_mcc_payment.to_datetime) OR (tbl_mcc_payment.from_datetime between \'' . $from_date . '\' and  \'' . $to_date . '\') OR (tbl_mcc_payment.to_datetime between \'' . $from_date . '\' and \'' . $to_date . '\'))');
        }

        $query->andFilterWhere(['=', 'CAST(tbl_mcc_payment.payment_date as date)', !empty($this->payment_date) ? date('Y-m-d', strtotime($this->payment_date)) : NULL]);
        // grid filtering conditions

        $query->andFilterWhere(['like', 'tbl_mcc_payment.kg_fat', $this->kg_fat])
                ->andFilterWhere(['like', 'tbl_mcc_payment.kg_snf', $this->kg_snf])
                ->andFilterWhere(['like', 'tbl_mcc_payment.total_qty', $this->total_qty])
                ->andFilterWhere(['like', 'tbl_mcc_payment.total_qty_amount', $this->total_qty_amount])
                ->andFilterWhere(['like', 'tbl_mcc_payment.minimum_qty', $this->minimum_qty])
                ->andFilterWhere(['like', 'tbl_mcc_payment.minimum_qty_amount', $this->minimum_qty_amount])
                ->andFilterWhere(['like', 'tbl_mcc_payment.total_loss', $this->total_loss])
                ->andFilterWhere(['like', 'tbl_mcc_payment.amount', $this->amount])
                ->andFilterWhere(['like', 'tbl_mcc_payment.addition', $this->addition])
                ->andFilterWhere(['like', 'tbl_mcc_payment.deduction', $this->deduction])
                ->andFilterWhere(['like', 'tbl_mcc_payment.net_payable', $this->net_payable])
                ->andFilterWhere(['like', 'tbl_mcc_payment.adjust_amount', $this->adjust_amount])
                ->andFilterWhere(['like', 'tbl_mcc_payment.final_pay', $this->final_pay])
                ->andFilterWhere(['like', 'tbl_mcc_payment.previous_hold', $this->previous_hold])
                ->andFilterWhere(['like', 'tbl_mcc_payment.previous_due', $this->previous_due])
                ->andFilterWhere(['like', 'tbl_mcc_payment.hold_amount', $this->hold_amount])
                ->andFilterWhere(['like', 'tbl_mcc_payment.adjust_remark', $this->adjust_remark])
                ->andFilterWhere(['like', 'tbl_mcc_payment.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_mcc_payment.status', $this->status])
                ->andFilterWhere(['like', 'tbl_mcc_payment.adjust_recovery', $this->adjust_recovery])
                ->andFilterWhere(['like', 'tbl_mcc_payment.recovery', $this->recovery]);
        //$query->orderBy(['tbl_mcc_payment.from_datetime' => SORT_DESC, 'tbl_customer_type.customer_desc' => SORT_ASC, 'tbl_mcc_payment.customer_code' => SORT_ASC]);

        return $dataProvider;
    }

}
