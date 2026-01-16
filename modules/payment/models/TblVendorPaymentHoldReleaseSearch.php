<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TblVendorPaymentHoldReleaseSearch represents the model behind the search form about `app\modules\payment\models\TblVendorPaymentHoldRelease`.
 */
class TblVendorPaymentHoldReleaseSearch extends TblVendorPaymentHoldRelease {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vendor_payment_hold_release_code','union_code','plant_code','mcc_plant_code','bmc_code','route_code','customer_type','customer_code','customer_name','payment_transaction_code','from_datetime','from_shift','to_datetime','to_shift','kg_fat','kg_snf','total_qty','rec_qty','rec_fat_kg','rec_snf_kg','amount','addition','deduction','net_payable','adjust_amount','previous_hold','previous_due','hold_amount','final_pay','adjust_remark','disburse_amount','payment_date','disburse_date','status','utr_no','pan_no','reference_no','process_date','reject_reason','registration_date','remarks','bank_name','bank_code','branch_name','branch_code','ifsc','bank_account_no','is_verified','bank_status','beneficiary_name','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','payment_cycle_code'], 'safe'],
            [['customer_ex_code','from_date', 'to_date',], 'safe'],
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
        $query = TblVendorPaymentHoldRelease::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'customerType']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_vendor_payment_hold_release', 'tbl_vendor_payment_hold_release', 'tbl_vendor_payment_hold_release');

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_code_ex', $this->customer_ex_code], ['like', 'tbl_customer_master.customer_code_ex', $this->customer_ex_code]]);


        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'CAST(tbl_vendor_payment_hold_release.from_datetime as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'CAST(tbl_vendor_payment_hold_release.to_datetime as date)', $to_date]);
        }


        $query->andFilterWhere(['=', 'CAST(tbl_vendor_payment_hold_release.payment_date as date)', !empty($this->payment_date) ? date('Y-m-d', strtotime($this->payment_date)) : NULL]);

        $query->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.kg_fat', $this->kg_fat])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.kg_snf', $this->kg_snf])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.total_qty', $this->total_qty])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.amount', $this->amount])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.addition', $this->addition])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.deduction', $this->deduction])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.net_payable', $this->net_payable])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.adjust_amount', $this->adjust_amount])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.final_pay', $this->final_pay])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.previous_hold', $this->previous_hold])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.previous_due', $this->previous_due])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.hold_amount', $this->hold_amount])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.adjust_remark', $this->adjust_remark])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_vendor_payment_hold_release.status', $this->status]);

        return $dataProvider;
    }
}
