<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblBonusPaymentPreviousData;

/**
 * TblBillHeadTransactionSearch represents the model behind the search form about `app\modules\vsp\models\TblBillHeadTransaction`.
 */
class TblBonusPaymentPreviousDataSearch extends TblBonusPaymentPreviousData {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['originating_type'], 'integer'],
            [['previous_data_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'customer_type', 'customer_code', 'bill_head_code', 'bill_head_for', 'transaction_date', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'remarks'], 'safe'],
            [['amount'], 'number'],
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
        $query = TblBonusPaymentPreviousData::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'customerType', 'billHeadCode', 'memberCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_bonus_payment_previous_data', 'tbl_bonus_payment_previous_data', 'tbl_bonus_payment_previous_data');

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'tbl_bonus_payment_previous_data.transaction_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'tbl_bonus_payment_previous_data.transaction_date', $to_date]);
        }
        if (!empty($this->transaction_date)) {
            $query->andFilterWhere(['and', ['>=', 'tbl_bonus_payment_previous_data.transaction_date', date('Y-m-d', strtotime($this->transaction_date))], ['<=', 'transaction_date', date('Y-m-d', strtotime($this->transaction_date))]]);
        }


        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name], ['like', 'tbl_member.member_name', $this->customer_name]]);
        $query->andFilterWhere(['or', ['like', 'tbl_customer_type.customer_desc', $this->customer_type], ['like', 'tbl_bonus_payment_previous_data.customer_type', $this->customer_type]]);

        $query->andFilterWhere(['like', 'tbl_bonus_payment_previous_data.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_bill_head.bill_head_name', $this->bill_head_code])
                ->andFilterWhere(['like', 'tbl_bonus_payment_previous_data.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_bonus_payment_previous_data.amount', $this->amount])
                ->andFilterWhere(['like', 'tbl_bonus_payment_previous_data.customer_code', $this->customer_code]);
        return $dataProvider;
    }

    public function gridsearch($params) {
        $this->load($params);
        $query = TblBonusPaymentPreviousData::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->joinWith(['customerType']);

        Yii::$app->general->filterByOrg($query, $this);

        $query->andWhere(['tbl_bonus_payment_previous_data.bmc_code' => $this->bmc_code]);
        $query->andFilterWhere(['like', 'tbl_bonus_payment_previous_data.customer_type', $this->customer_type]);

        return $dataProvider;
    }

}
