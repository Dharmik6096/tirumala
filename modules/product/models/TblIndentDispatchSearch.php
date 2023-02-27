<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblIndentDispatch;

/**
 * TblIndentDispatchSearch represents the model behind the search form about `app\modules\product\models\TblIndentDispatch`.
 */
class TblIndentDispatchSearch extends TblIndentDispatch {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['indent_dispatch_code', 'challan_date', 'reference_no', 'dispatch_date', 'customer_type', 'customer_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'vehicle_no', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'indent_code', 'product_code', 'status', 'rate', 'amount', 'discount_amount', 'dispatch_qty'], 'safe'],
            [['originating_type'], 'integer'],
            [['from_date', 'to_date'], 'safe'],
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
        $query = TblIndentDispatch::find();

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
        $query->joinWith(['routeCode', 'vehicleCode', 'productCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_indent_dispatch', 'tbl_indent_dispatch', 'tbl_indent_dispatch');

        // grid filtering conditions
        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'dispatch_date', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'dispatch_date', $to_date]);
        }

        if (!empty($this->dispatch_date)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), dispatch_date, 126)', date('Y-m-d', strtotime($this->dispatch_date))]);
        }

        $query->andFilterWhere(['like', 'indent_dispatch_code', $this->indent_dispatch_code])
                ->andFilterWhere(['like', 'reference_no', $this->reference_no])
                ->andFilterWhere(['like', 'customer_type', $this->customer_type])
                ->andFilterWhere(['like', 'customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_route_mapping.route_name', $this->route_code])
                ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code])
                ->andFilterWhere(['like', 'tbl_indent_dispatch.dispatch_qty', $this->dispatch_qty])
                ->andFilterWhere(['like', 'tbl_vehicle_master.parsing_no', $this->vehicle_no]);

        return $dataProvider;
    }

}
