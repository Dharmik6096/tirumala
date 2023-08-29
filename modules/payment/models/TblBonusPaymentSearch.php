<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblBonusPayment;

/**
 * TblBonusPaymentSearch represents the model behind the search form about `app\modules\payment\models\TblBonusPayment`.
 */
class TblBonusPaymentSearch extends TblBonusPayment {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bonus_payment_code', 'bonus_payment_summary_code', 'is_verified', 'originating_type'], 'safe'],
                [['customer_type', 'customer_code', 'customer_name', 'status', 'disburse_date', 'payment_date', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'beneficiary_name', 'utr_no', 'reference_no', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['kg_fat', 'kg_snf', 'avg_fat', 'avg_snf', 'qty', 'amount', 'addition', 'deduction', 'net_payable', 'disburse_amount'], 'number'],
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
        $query = TblBonusPayment::find()->where(['bonus_payment_summary_code' => $this->bonus_payment_summary_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->orderBy(['tbl_bonus_payment.customer_code' => SORT_DESC]);

        return $dataProvider;
    }

}
