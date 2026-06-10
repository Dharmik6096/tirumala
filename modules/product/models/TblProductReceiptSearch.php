<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductReceipt;

/**
 * TblProductReceiptSearch represents the model behind the search form about `app\modules\product\models\TblProductReceipt`.
 */
class TblProductReceiptSearch extends TblProductReceipt {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_receipt_code', 'from_date', 'to_date', 'customer_name', 'grn_no', 'grn_date', 'challan_no', 'challan_date', 'description', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['challan_verified', 'originating_type', 'bill_no'], 'safe'],
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
        $query = TblProductReceipt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'customerType', 'mainCustomerCode', 'bmcCode', 'bmcCode.tblMccPlant']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_receipt', 'tbl_mcc_plant', 'tbl_product_receipt');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->grn_date)) {
            $query->andFilterWhere(['like', 'tbl_product_receipt.grn_date', date('Y-m-d', strtotime($this->grn_date))]);
        }
        if (!empty($this->challan_date)) {
            $query->andFilterWhere(['like', 'tbl_product_receipt.challan_date', date('Y-m-d', strtotime($this->challan_date))]);
        }

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_product_receipt.grn_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_product_receipt.grn_date as date)', $to_date]);
        }
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);

        // grid filtering conditions
        $query->andFilterWhere([
            'challan_verified' => $this->challan_verified,
        ]);

        $query->andFilterWhere(['like', 'tbl_product_receipt.vendor_code', $this->vendor_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->vendor_type])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_product_receipt.description', $this->description])
                ->andFilterWhere(['like', 'tbl_product_receipt.product_receipt_code', $this->product_receipt_code])
                ->andFilterWhere(['like', 'tbl_product_receipt.grn_no', $this->grn_no])
                ->andFilterWhere(['like', 'tbl_product_receipt.challan_no', $this->challan_no])
                ->andFilterWhere(['like', 'tbl_product_receipt.bill_no', $this->bill_no]);

        return $dataProvider;
    }

}
