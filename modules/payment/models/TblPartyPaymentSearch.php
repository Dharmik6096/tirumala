<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblPartyPayment;

class TblPartyPaymentSearch extends TblPartyPayment {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['party_payment_code', 'union_code', 'payment_type', 'party_master_code', 'from_date', 'to_date', 'disp_qty', 'disp_kg_fat'], 'safe'],
            [['disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'no_of_days'], 'safe'],
            [['total_qty', 'avg_fat', 'avg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'total_addition', 'net_amount'], 'safe'],
            [['adjust_amount', 'adjust_remark', 'final_amount', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc'], 'safe'],
            [['bank_account_no', 'beneficiary_name', 'is_verified', 'payment_date', 'status', 'disburse_amount', 'disburse_date', 'utr_no'], 'safe'],
            [['reference_no', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_at', 'created_by'], 'safe'],
            [['updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['from_date','to_date'],'required', 'on' => 'disburse']
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
        $query = TblPartyPayment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->andFilterWhere(['=', 'CAST(tbl_party_payment.payment_date as date)', !empty($this->payment_date) ? date('Y-m-d', strtotime($this->payment_date)) : NULL]);

        $query->andFilterWhere(['like', 'tbl_party_payment.payment_type', $this->payment_type])
                ->andFilterWhere(['like', 'tbl_party_payment.disp_kg_fat', $this->disp_kg_fat])
                ->andFilterWhere(['like', 'tbl_party_payment.disp_kg_snf', $this->disp_kg_snf])
                ->andFilterWhere(['like', 'tbl_party_payment.disp_qty', $this->disp_qty])
                ->andFilterWhere(['like', 'tbl_party_payment.rec_kg_fat', $this->rec_kg_fat])
                ->andFilterWhere(['like', 'tbl_party_payment.rec_kg_snf', $this->rec_kg_snf])
                ->andFilterWhere(['like', 'tbl_party_payment.rec_qty', $this->rec_qty])
                ->andFilterWhere(['like', 'tbl_party_payment.rd_kg_fat_diff', $this->rd_kg_fat_diff])
                ->andFilterWhere(['like', 'tbl_party_payment.rd_kg_snf_diff', $this->rd_kg_snf_diff])
                ->andFilterWhere(['like', 'tbl_party_payment.rd_qty_diff', $this->rd_qty_diff])
                ->andFilterWhere(['like', 'tbl_party_payment.avg_fat', $this->avg_fat])
                ->andFilterWhere(['like', 'tbl_party_payment.avg_snf', $this->avg_snf])
                ->andFilterWhere(['like', 'tbl_party_payment.avg_rate', $this->avg_rate])
                ->andFilterWhere(['like', 'tbl_party_payment.total_amount', $this->total_amount])
                ->andFilterWhere(['like', 'tbl_party_payment.total_addition', $this->total_addition])
                ->andFilterWhere(['like', 'tbl_party_payment.total_deduction', $this->total_deduction])
                ->andFilterWhere(['like', 'tbl_party_payment.final_amount', $this->final_amount])
                ->andFilterWhere(['like', 'tbl_party_payment.net_amount', $this->net_amount])
                ->andFilterWhere(['like', 'tbl_party_payment.status', $this->status]);

        $query->orderBy(['tbl_party_payment.from_date' => SORT_DESC]);

        return $dataProvider;
    }

    public function disbursesearch() {
        $query = TblPartyPayment::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!$this->validate()) {
             $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['>=', 'CAST(tbl_party_payment.from_date as date)', $this->from_date]);  
        $query->andFilterWhere(['<=', 'CAST(tbl_party_payment.to_date as date)', $this->to_date]);
        return $dataProvider;
    }
}
