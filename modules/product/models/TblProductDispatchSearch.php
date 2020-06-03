<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductDispatch;

/**
 * TblProductDispatchSearch represents the model behind the search form about `app\modules\product\models\TblProductDispatch`.
 */
class TblProductDispatchSearch extends TblProductDispatch {

    public $from_date, $to_date, $customer_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['challan_no', 'challan_date', 'from_date', 'to_date', 'customer_name', 'reference_no', 'dispatch_date', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'vehicle_no', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['challan_verified', 'originating_type'], 'integer'],
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
//        $flag[0 => without requisition, 1 => With Requisition]
        $query = TblProductDispatch::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'customerType', 'mainCustomerCode', 'bmcCode', 'mccCode', 'plantCode', 'routeCode', 'tblProductDispatchTransaction', 'vehicleCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_dispatch', 'tbl_mcc_plant', 'tbl_product_dispatch');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->challan_date)) {
            $query->andFilterWhere(['like', 'tbl_product_dispatch.challan_date', date('Y-m-d', strtotime($this->challan_date))]);
        }
        if (!empty($this->dispatch_date)) {
            $query->andFilterWhere(['like', 'tbl_product_dispatch.dispatch_date', date('Y-m-d', strtotime($this->dispatch_date))]);
        }

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_product_dispatch.dispatch_date as date)', $from_date]);
        }

        $query->andFilterWhere(['or',
                ['like', 'tbl_dcs.dcs_name', $this->customer_name],
                ['like', 'tbl_bmc.bmc_name', $this->customer_name]
        ]);
        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_product_dispatch.dispatch_date as date)', $to_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'challan_verified' => $this->challan_verified,
        ]);

        if (Yii::$app->request->get('flag') == 0) {
            $query->andWhere(['is', 'tbl_product_dispatch_transaction.product_requisition_code', null]);
        } else {
            $query->andWhere(['is not', 'tbl_product_dispatch_transaction.product_requisition_code', null]);
        }
        $query->andFilterWhere(['like', 'tbl_product_dispatch.challan_no', $this->challan_no])
                ->andFilterWhere(['like', 'tbl_product_dispatch.reference_no', $this->reference_no])
                ->andFilterWhere(['like', 'tbl_route_mapping.route_name', $this->route_code])
                ->andFilterWhere(['like', 'tbl_vehicle_master.parsing_no', $this->vehicle_no])
                ->andFilterWhere(['like', 'tbl_product_dispatch.vendor_code', $this->vendor_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->vendor_type])
                ->andFilterWhere(['like', 'tbl_plant.name', $this->plant_name])
                ->andFilterWhere(['like', 'tbl_mcc_plant.name', $this->mcc_name]);

        return $dataProvider;
    }

}
