<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblProductSale;
use app\modules\payment\models\TblPaymentCycleApplicability;

/**
 * TblProductSaleSearch represents the model behind the search form about `app\modules\payment\models\TblProductSale`.
 */
class TblProductSaleSearch extends TblProductSale {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_sale_code', 'dcs_code', 'union_code', 'member_code', 'invoice_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'bmc_code', 'customer_type', 'customer_code', 'customer_type', 'customer_name', 'payment_mode', 'customer_name', 'from_date', 'to_date'], 'safe'],
                [['amount', 'other_amount', 'discount', 'paid_amount', 'amount_due'], 'number'],
                [['is_installment', 'no_of_installment'], 'integer'],
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
        $query = TblProductSale::find()->select('tset');
//        $query->select('test');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'customerType', 'mainCustomerCode', 'memberCode', 'bmcCode', 'bmcCode.tblMccPlant']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_sale', 'tbl_mcc_plant', 'tbl_product_sale');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
//        if ($this->member_code) {
//            $query->joinWith(['memberCode']);
//            $query->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code]);
//        }
        if (!empty($this->invoice_date))
            $query->andFilterWhere(['like', 'tbl_product_sale.invoice_date', date('Y-m-d', strtotime($this->invoice_date))]);
        // grid filtering conditions


        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_product_sale.invoice_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_product_sale.invoice_date as date)', $to_date]);
        }
        $query->andFilterWhere([
            'tbl_product_sale.amount' => $this->amount,
            'tbl_product_sale.other_amount' => $this->other_amount,
            'tbl_product_sale.discount' => $this->discount,
            'tbl_product_sale.paid_amount' => $this->paid_amount,
            'tbl_product_sale.amount_due' => $this->amount_due,
            'tbl_product_sale.is_installment' => $this->is_installment,
            'tbl_product_sale.no_of_installment' => $this->no_of_installment,
        ]);
        if (!empty($this->payment_mode) || $this->payment_mode == '0') {
            $query->andFilterWhere(['tbl_product_sale.payment_mode' => (int) $this->payment_mode]);
        }
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name], ['like', 'tbl_member.member_name', $this->customer_name]]);

        $query->andFilterWhere(['like', 'tbl_product_sale.product_sale_code', $this->product_sale_code])
                ->andFilterWhere(['like', 'tbl_product_sale.customer_type', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_product_sale.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code]);

        return $dataProvider;
    }

    public function searchSaleDetails($params) {
        $query = TblProductSaleDetails::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['productSaleCode', 'saleInstallments']);
        $query->andWhere(['tbl_product_sale.bmc_code' => $this->bmc_code]);
        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->invoice_date)) {
            $model = new TblPaymentCycleApplicability();
            $model->applicable_type = $this->customer_type;
            $model->applicable_code = $this->bmc_code;
            $model->applicable_for = 'BMC';
            $modelData = $model->getApplicablePaymentCycle(date('Y-m-d', strtotime($this->invoice_date)));
            if (!empty($modelData)) {
                $query->andFilterWhere(['or', ['between', 'cast(tbl_product_sale.invoice_date as date)', date('Y-m-d', strtotime($modelData->from_date)), date('Y-m-d', strtotime($modelData->to_date))], ['between', 'tbl_product_sale_installment.installment_date', date('Y-m-d', strtotime($modelData->from_date)), date('Y-m-d', strtotime($modelData->to_date))]]);
            } else {
                $query->andFilterWhere(['or', ['cast(tbl_product_sale.invoice_date as date)' => date('Y-m-d', strtotime($this->invoice_date))], ['tbl_product_sale_installment.installment_date' => date('Y-m-d', strtotime($this->invoice_date))]]);
            }
        }
        $query->andFilterWhere([
            'tbl_product_sale.customer_type' => $this->customer_type,
            'tbl_product_sale.customer_code' => $this->customer_code,
            'tbl_product_sale.union_code' => $this->union_code,
        ]);
        return $dataProvider;
    }

}
