<?php

namespace app\modules\payment\models;

use yii\data\ActiveDataProvider;

use Yii;
use yii\db\Expression;
class TblPaymentTransactionSearch extends TblPaymentTransaction {
    public $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_transaction_code','union_code','code','name','type','dcs_payment_cycle_applicabilty_code','total_amount','total_deduction','final_amount','disburse_amount','disburse_date','payment_date','approved_by','status','transfer_mode','error_code','error_log','ack','created_at','created_by','updated_at','updated_by','qty','avg_fat','avg_snf','kg_fat','kg_snf','avg_rate','dcs_payment_cycle_code','bank_name','bank_code','branch_name','branch_code','ifsc','bank_account_no','is_verified','member_count','mobile_no','sms_status','sms_log','sms_timestamp','sms_msgid','is_file','file_id','file_datetime','utr_no','reference_no','process_date','reject_reason','bank_status','dcs_code','file_name','txn_status','txn_ref_code','txn_error_code','error_desc','txn_status_code','txn_status_desc','pick_datetime','cron_pick_datetime','response_datetime','response_msg','union_bank_payment_code','is_approved','approved_at','payment_transaction_approval_code'], 'safe'],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'type', 'from_date', 'to_date'], 'safe'],
                [['plant_code', 'mcc_plant_code', 'from_date', 'to_date'], 'required']
        ];
    }

    public function searchRejectReinitiate($params) {
        $query = TblPaymentTransaction::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_ASC]],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $query->where(['bank_status' => ['FAILED']]);
        if (empty($this->from_date)) {
            $this->from_date = date('d-m-Y');
        }
        if (empty($this->to_date)) {
            $this->to_date = date('d-m-Y');
        }
        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'cast(payment_date as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'cast(payment_date as date)', $to_date]);
        }
        $query->andFilterWhere(['mcc_plant_code' => $this->mcc_plant_code])
            ->andFilterWhere(['bmc_code' => $this->bmc_code])
            ->andFilterWhere(['type' => $this->type]);
            
        return $dataProvider;
    }
}