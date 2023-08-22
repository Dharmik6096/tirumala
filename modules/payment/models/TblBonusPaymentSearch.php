<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblBonusPayment;

/**
 * TblBonusPaymentSearch represents the model behind the search form about `app\modules\payment\models\TblBonusPayment`.
 */
class TblBonusPaymentSearch extends TblBonusPayment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bonus_payment_code', 'bonus_payment_summary_code', 'is_verified', 'originating_type'], 'safe'],
            [['customer_type', 'customer_code', 'customer_name', 'status', 'disburse_date', 'payment_date', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'beneficiary_name', 'utr_no', 'reference_no', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['kg_fat', 'kg_snf', 'avg_fat', 'avg_snf', 'qty', 'amount', 'addition', 'deduction', 'net_payable', 'disburse_amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblBonusPayment::find();

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
            'bonus_payment_code' => $this->bonus_payment_code,
            'bonus_payment_summary_code' => $this->bonus_payment_summary_code,
            'kg_fat' => $this->kg_fat,
            'kg_snf' => $this->kg_snf,
            'avg_fat' => $this->avg_fat,
            'avg_snf' => $this->avg_snf,
            'qty' => $this->qty,
            'amount' => $this->amount,
            'addition' => $this->addition,
            'deduction' => $this->deduction,
            'net_payable' => $this->net_payable,
            'disburse_amount' => $this->disburse_amount,
            'disburse_date' => $this->disburse_date,
            'payment_date' => $this->payment_date,
            'is_verified' => $this->is_verified,
            'process_date' => $this->process_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'customer_type', $this->customer_type])
            ->andFilterWhere(['like', 'customer_code', $this->customer_code])
            ->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'bank_code', $this->bank_code])
            ->andFilterWhere(['like', 'branch_name', $this->branch_name])
            ->andFilterWhere(['like', 'branch_code', $this->branch_code])
            ->andFilterWhere(['like', 'ifsc', $this->ifsc])
            ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
            ->andFilterWhere(['like', 'beneficiary_name', $this->beneficiary_name])
            ->andFilterWhere(['like', 'utr_no', $this->utr_no])
            ->andFilterWhere(['like', 'reference_no', $this->reference_no])
            ->andFilterWhere(['like', 'reject_reason', $this->reject_reason])
            ->andFilterWhere(['like', 'bank_status', $this->bank_status])
            ->andFilterWhere(['like', 'payment_transaction_code', $this->payment_transaction_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
