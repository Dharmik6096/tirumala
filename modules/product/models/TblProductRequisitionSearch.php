<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductRequisition;

/**
 * TblProductRequisitionSearch represents the model behind the search form about `app\modules\product\models\TblProductRequisition`.
 */
class TblProductRequisitionSearch extends TblProductRequisition {

    public $from_date, $to_date, $customer_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_requisition_code', 'from_date', 'to_date', 'customer_name', 'req_date', 'description', 'status', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['originating_type'], 'integer'],
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
        $query = TblProductRequisition::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'customerType', 'mainCustomerCode', 'bmcCode', 'bmcCode.tblMccPlant']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_requisition', 'tbl_mcc_plant', 'tbl_product_requisition');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->req_date)) {
            $query->andFilterWhere(['like', 'tbl_product_requisition.req_date', date('Y-m-d', strtotime($this->req_date))]);
        }

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_product_requisition.req_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_product_requisition.req_date as date)', $to_date]);
        }
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product_requisition.status' => $this->status,
        ]);
        $query->andFilterWhere(['like', 'tbl_product_requisition.vendor_code', $this->vendor_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->vendor_type])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_product_requisition.description', $this->description]);

        return $dataProvider;
    }

}
