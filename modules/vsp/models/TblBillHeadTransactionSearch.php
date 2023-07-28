<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblBillHeadTransaction;

/**
 * TblBillHeadTransactionSearch represents the model behind the search form about `app\modules\vsp\models\TblBillHeadTransaction`.
 */
class TblBillHeadTransactionSearch extends TblBillHeadTransaction {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bill_head_txn_code', 'no_installment', 'originating_type'], 'integer'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'customer_type', 'customer_code', 'bill_head_code', 'payment_cycle_type', 'bill_head_for', 'transaction_date', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
                [['amount', 'installment_amount', 'paid_amount', 'unpaid_amount'], 'number'],
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
        $query = TblBillHeadTransaction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'customerType', 'billHeadCode', 'installmentCode', 'memberCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_bill_head_transaction', 'tbl_bill_head_transaction', 'tbl_bill_head_transaction');

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'tbl_bill_head_transaction.transaction_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'tbl_bill_head_transaction.transaction_date', $to_date]);
        }
        if (!empty($this->transaction_date))
            $query->andFilterWhere(['and', ['>=', 'tbl_bill_head_transaction.transaction_date', date('Y-m-d', strtotime($this->transaction_date))], ['<=', 'transaction_date', date('Y-m-d', strtotime($this->transaction_date))]]);


        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name], ['like', 'tbl_member.member_name', $this->customer_name]]);
        $query->andFilterWhere(['or', ['like', 'tbl_customer_type.customer_desc', $this->customer_type], ['like', 'tbl_bill_head_transaction.customer_type', $this->customer_type]]);

        $query->andFilterWhere(['like', 'tbl_bill_head_transaction.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_bill_head.bill_head_name', $this->bill_head_code])
                ->andFilterWhere(['like', 'tbl_bill_head_transaction.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_bill_head_transaction.amount', $this->amount])
                ->andFilterWhere(['like', 'tbl_bill_head_transaction.installment_amount', $this->installment_amount])
                ->andFilterWhere(['like', 'tbl_bill_head_transaction.paid_amount', $this->paid_amount])
                ->andFilterWhere(['like', 'tbl_bill_head_transaction.unpaid_amount', $this->unpaid_amount])
                ->andFilterWhere(['like', 'tbl_bill_head_transaction.no_installment', $this->no_installment])
                ->andFilterWhere(['like', 'tbl_bill_head_transaction.customer_code', $this->customer_code]);
        return $dataProvider;
    }

    public function gridsearch($params) {
        $this->load($params);
        $query = TblBillHeadTransaction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->joinWith(['customerType']);
        $query->join('LEFT JOIN', 'tbl_bill_head_transaction_installment', 'tbl_bill_head_transaction_installment.bill_head_txn_code = tbl_bill_head_transaction.bill_head_txn_code');

        Yii::$app->general->filterByOrg($query, $this);

        $query->andWhere(['tbl_bill_head_transaction.bmc_code' => $this->bmc_code]);
        $query->andFilterWhere(['like', 'tbl_bill_head_transaction.customer_type', $this->customer_type]);

        $query->orderBy(['tbl_bill_head_transaction_installment.installment_date' => SORT_DESC, 'tbl_customer_type.customer_desc' => SORT_ASC, 'tbl_bill_head_transaction.customer_code' => SORT_ASC]);
        return $dataProvider;
    }

    public function installmentsearch($params) {
        $this->load($params);
        $query = TblBillHeadTransactionInstallment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_bill_head_transaction_installment');
        $query->andWhere([
            'bill_head_txn_code' => $this->bill_head_txn_code
        ]);
        return $dataProvider;
    }

    public function membergridsearch($params) {
        $this->load($params);
        $query = TblBillHeadTransaction::find();
        $query->andWhere(['tbl_bill_head_transaction.customer_type' => 'member']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->join('LEFT JOIN', 'tbl_bill_head_transaction_installment', 'tbl_bill_head_transaction_installment.bill_head_txn_code = tbl_bill_head_transaction.bill_head_txn_code');

        Yii::$app->general->filterByOrg($query, $this);
        $query->andWhere(['tbl_bill_head_transaction.bmc_code' => $this->bmc_code]);
        $query->orderBy(['tbl_bill_head_transaction_installment.installment_date' => SORT_DESC, 'tbl_bill_head_transaction.customer_code' => SORT_ASC]);
        return $dataProvider;
    }

}
