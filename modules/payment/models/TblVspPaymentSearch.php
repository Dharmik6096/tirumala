<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblVspPayment;

/**
 * TblVspPaymentSearch represents the model behind the search form about `app\modules\payment\models\TblVspPayment`.
 */
class TblVspPaymentSearch extends TblVspPayment {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vsp_payment_code', 'payment_cycle_code', 'payment_cycle_applicabilty_code', 'payment_date', 'from_date', 'to_date', 'previous_hold', 'previous_due', 'hold_amount'], 'safe'],
            [['dcs_code', 'union_code', 'adjust_remark', 'created_at', 'created_by', 'updated_at', 'updated_by', 'status', 'customer_code', 'customer_type', 'customer_name', 'customer_ex_code'], 'safe'],
            [['kg_fat', 'kg_snf', 'total_qty', 'total_loss', 'amount', 'addition', 'deduction', 'net_payable', 'adjust_amount', 'final_pay'], 'number'],
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
        $query = TblVspPayment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'customerType', 'paymentCycleCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_vsp_payment', 'tbl_vsp_payment', 'tbl_vsp_payment');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_code_ex', $this->customer_ex_code], ['like', 'tbl_customer_master.customer_code_ex', $this->customer_ex_code]]);


        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'tbl_payment_cycle.from_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'tbl_payment_cycle.from_date', $to_date]);
        }
        $query->andFilterWhere(['=', 'CAST(tbl_vsp_payment.payment_date as date)', !empty($this->payment_date) ? date('Y-m-d', strtotime($this->payment_date)) : NULL]);
        // grid filtering conditions

        $query->andFilterWhere(['like', 'kg_fat', $this->kg_fat])
                ->andFilterWhere(['like', 'kg_snf', $this->kg_snf])
                ->andFilterWhere(['like', 'total_qty', $this->total_qty])
                ->andFilterWhere(['like', 'total_loss', $this->total_loss])
                ->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'addition', $this->addition])
                ->andFilterWhere(['like', 'deduction', $this->deduction])
                ->andFilterWhere(['like', 'net_payable', $this->net_payable])
                ->andFilterWhere(['like', 'adjust_amount', $this->adjust_amount])
                ->andFilterWhere(['like', 'final_pay', $this->final_pay])
                ->andFilterWhere(['like', 'previous_hold', $this->previous_hold])
                ->andFilterWhere(['like', 'previous_due', $this->previous_due])
                ->andFilterWhere(['like', 'hold_amount', $this->hold_amount])
                ->andFilterWhere(['like', 'adjust_remark', $this->adjust_remark])
                ->andFilterWhere(['like', 'tbl_vsp_payment.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
                ->andFilterWhere(['like', 'status', $this->status]);
        $query->orderBy(['tbl_payment_cycle.from_date' => SORT_DESC, 'tbl_customer_type.customer_desc' => SORT_ASC, 'tbl_vsp_payment.customer_code' => SORT_ASC]);

        return $dataProvider;
    }

}
