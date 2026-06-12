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

    public $from_date, $to_date, $rate, $tax_amount, $commission, $product_name, $product_desc;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_sale_code', 'dcs_code', 'union_code', 'member_code', 'invoice_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'bmc_code', 'customer_type', 'customer_code', 'customer_type', 'customer_name', 'payment_mode', 'customer_name', 'from_date', 'to_date', 'rate', 'tax_amount', 'commission', 'product_name', 'product_desc', 'voucher_code'], 'safe'],
            [['amount', 'other_amount', 'discount', 'paid_amount', 'amount_due'], 'number'],
            [['is_installment', 'no_of_installment'], 'integer'],
            [['plant_code', 'union_code', 'bmc_code', 'mcc_plant_code', 'from_date', 'to_date'], 'required', 'on' => ['memberBulkDelete', 'memberBulkDeleteApproval']],
            [['dcs_code'], 'required', 'on' => ['memberBulkDelete']],
            [['plant_code', 'union_code', 'bmc_code', 'mcc_plant_code', 'from_date', 'to_date', 'customer_type'], 'required', 'on' => ['vendorBulkDelete', 'vendorBulkDeleteApproval']],
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
        $query = TblProductSale::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
//        $query->joinWith(['dcsCode', 'customerType', 'mainCustomerCode', 'memberCode', 'bmcCode', 'bmcCode.tblMccPlant']);
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'memberCode', 'bmcCode']);
        $query->join('LEFT JOIN', 'tbl_dcs as dcs', 'dcs.dcs_code = tbl_product_sale.dcs_code');

        if (!empty($this->invoice_date))
            $query->andFilterWhere(['tbl_product_sale.invoice_date' => date('Y-m-d', strtotime($this->invoice_date)).' 00:00:00']);

        if (!empty($this->created_at)) {
            $created_at_start = date('Y-m-d', strtotime($this->created_at)) . ' 00:00:00';
            $created_at_end = date('Y-m-d', strtotime($this->created_at)) . ' 23:59:00';

            $query->andFilterWhere(['and',
                ['>=', 'tbl_product_sale.created_at', $created_at_start],
                ['<=', 'tbl_product_sale.created_at', $created_at_end]
            ]);
        }

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'tbl_product_sale.invoice_date', $from_date.' 00:00:00']);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'tbl_product_sale.invoice_date', $to_date.' 23:59:00']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_sale', 'tbl_product_sale', 'tbl_product_sale');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
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
        $query->andFilterWhere(['or', ['like', 'dcs.ref_code', $this->dcs_code], ['like', 'tbl_dcs.ref_code', $this->dcs_code]]);
        $query->andFilterWhere(['like', 'tbl_product_sale.product_sale_code', $this->product_sale_code])
                ->andFilterWhere(['like', 'tbl_product_sale.customer_type', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_product_sale.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code]);

        return $dataProvider;
    }

    public function searchSaleDetails($params, $break_query = false) {
        $query = TblProductSaleTransaction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate() || $break_query) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['productSaleCode', 'saleInstallments']);
        if (!empty($this->invoice_date)) {
            $model = new TblPaymentCycleApplicability();
            $model->applicable_type = $this->customer_type;
            $model->applicable_code = $this->bmc_code;
            $model->applicable_for = 'BMC';
            $modelData = $model->getApplicablePaymentCycle(date('Y-m-d', strtotime($this->invoice_date)));
            if (!empty($modelData)) {
                $from_date = date('Y-m-d', strtotime($modelData->from_date)) . ' 00:00:00';
                $to_date = date('Y-m-d', strtotime($modelData->to_date)) . ' 23:59:00';

                $query->andFilterWhere(['or',
                    ['and', ['>=', 'tbl_product_sale.invoice_date', $from_date], ['<=', 'tbl_product_sale.invoice_date', $to_date]],
                    ['and', ['>=', 'tbl_product_sale_installment.installment_date', $from_date], ['<=', 'tbl_product_sale_installment.installment_date', $to_date]]
                ]);
            } else {
                $query->andFilterWhere(['or', ['tbl_product_sale.invoice_date' => date('Y-m-d', strtotime($this->invoice_date)).' 00:00:00'], ['tbl_product_sale_installment.installment_date' => date('Y-m-d', strtotime($this->invoice_date))]]);
            }
        }
        $query->andFilterWhere(['tbl_product_sale.customer_code' => $this->customer_code]);
        $query->andWhere(['tbl_product_sale.bmc_code' => $this->bmc_code]);
        $query->andFilterWhere(['tbl_product_sale.customer_type' => $this->customer_type]);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_sale', 'tbl_product_sale', 'tbl_product_sale');
        $query->andFilterWhere(['tbl_product_sale.union_code' => $this->union_code]);
        return $dataProvider;
    }

    public function searchForDelete($params, $approval = FALSE) {
        $query = TblProductSale::find()->alias('ps');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'memberCode', 'bmcCode']);
        if ($approval == 'deleteGrid') {
            $query->join('join', 'tbl_payment_cycle_applicability pca', 'pca.applicable_code=ps.bmc_code'
                . ' and cast(ps.invoice_date as date) between cast(pca.from_date as date) and cast(pca.to_date as date)'
                . ' and pca.applicable_for=\'BMC\' and pca.applicable_type = case when ps.customer_type=\'Member\'then \'DCS\' else ps.customer_type end'
                . ' and pca.data_lock_member= case when ps.customer_type=\'Member\' then \'0\' else pca.data_lock_member end'
                . ' and pca.billing_lock_member= case when ps.customer_type=\'Member\' then \'0\' else pca.billing_lock_member end'
                . ' and pca.data_lock_bmc= case when ps.customer_type<>\'Member\' then \'0\' else pca.data_lock_bmc end'
                . ' and pca.billing_lock_bmc= case when ps.customer_type<>\'Member\' then \'0\' else pca.billing_lock_bmc end');
        }

        Yii::$app->general->filterByOrg($query, $this, 'ps', 'ps', 'ps');
        $flag = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'product_sale_delete_approval', 'PORTAL');
        if ($flag == 1) {
            if ($approval === TRUE) {
                $query->leftJoin('tbl_product_sale_alias psa', 'psa.product_sale_code = ps.product_sale_code');
                $query->addSelect(['ps.*', 'psa.error_desc']);
                $query->andWhere(['=', 'psa.action_perform', 'DELETE']);
            } else if ($approval == 'deleteGrid') {
                $subQuery = TblProductSaleAlias::find()->select('product_sale_code')->where(['action_perform' => 'delete'])->column();
                $query->andWhere(['NOT IN', 'ps.product_sale_code', $subQuery]);
            }
        }

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(ps.invoice_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(ps.invoice_date as date)', $to_date]);
        }        

        if (!empty($this->dcs_code)) {
            $query->andFilterWhere(['ps.dcs_code' => $this->dcs_code]);
        }
        if (!empty($this->customer_type)) {
            $query->andFilterWhere(['ps.customer_type' => $this->customer_type]);
        }

        $query->andFilterWhere(['ps.plant_code' => $this->plant_code])
                ->andFilterWhere(['ps.mcc_plant_code' => $this->mcc_plant_code])
                ->andFilterWhere(['ps.bmc_code' => $this->bmc_code]);

        $query->andFilterWhere(['ps.payment_mode' => '1']);

        return $dataProvider;
    }

    public function searchSaleTransaction($params) {
        $query = TblProductSaleTransaction::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['productSaleCode', 'productCode', 'productSaleCode.dcsCode', 'productSaleCode.mainCustomerCode', 'productSaleCode.memberCode', 'productSaleCode.bmcCode']);
        $query->join('LEFT JOIN', 'tbl_dcs as dcs', 'dcs.dcs_code = tbl_product_sale.dcs_code');

        if (!empty($this->invoice_date))
            $query->andFilterWhere(['tbl_product_sale.invoice_date' => date('Y-m-d', strtotime($this->invoice_date)).' 00:00:00']);

        if (!empty($this->created_at)) {
            $created_at_start = date('Y-m-d', strtotime($this->created_at)) . ' 00:00:00';
            $created_at_end = date('Y-m-d', strtotime($this->created_at)) . ' 23:59:00';

            $query->andFilterWhere(['and',
                ['>=', 'tbl_product_sale.created_at', $created_at_start],
                ['<=', 'tbl_product_sale.created_at', $created_at_end]
            ]);
        }

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'tbl_product_sale.invoice_date', $from_date.' 00:00:00']);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'tbl_product_sale.invoice_date', $to_date.' 23:59:00']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_sale', 'tbl_product_sale', 'tbl_product_sale');
        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'tbl_product_sale_transaction.amount' => $this->amount,
            'tbl_product_sale_transaction.rate' => $this->rate,
            'tbl_product_sale.other_amount' => $this->other_amount,
            'tbl_product_sale_transaction.discount' => $this->discount,
            'tbl_product_sale.paid_amount' => $this->paid_amount,
            'tbl_product_sale_transaction.tax_amount' => $this->tax_amount,
            'tbl_product_sale_transaction.commission' => $this->commission,
            'tbl_product_sale.is_installment' => $this->is_installment,
            'tbl_product_sale.no_of_installment' => $this->no_of_installment,
        ]);
        if (!empty($this->payment_mode) || $this->payment_mode == '0') {
            $query->andFilterWhere(['tbl_product_sale.payment_mode' => (int) $this->payment_mode]);
        }
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name], ['like', 'tbl_member.member_name', $this->customer_name]]);
        $query->andFilterWhere(['or', ['like', 'dcs.ref_code', $this->dcs_code], ['like', 'tbl_dcs.ref_code', $this->dcs_code]]);
        $query->andFilterWhere(['like', 'tbl_product_sale.product_sale_code', $this->product_sale_code])
                ->andFilterWhere(['like', 'tbl_product_sale.customer_type', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_product_sale.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_name])
                ->andFilterWhere(['like', 'tbl_product.product_desc', $this->product_desc])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code]);

        return $dataProvider;
    }
}
