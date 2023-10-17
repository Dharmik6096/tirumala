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
        $query = TblPartyPaymentDetail::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        return $dataProvider;
    }

    public function disbursesearch() {
        $query = TblPartyPaymentDetail::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

}
