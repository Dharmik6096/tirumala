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

    public $from_date, $to_date, $dispatch_center_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_requisition_code', 'from_date', 'to_date', 'customer_name', 'req_date', 'description', 'status', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'plant_name', 'mcc_name', 'route_code', 'dispatch_center_code'], 'safe'],
                [['originating_type'], 'integer'],
                [['union_code'], 'required', 'on' => 'searchdispatch'],
//                [['dcs_code'], 'required', 'when' => function ($model) {
//                    return $model->vendor_type == 'DCS';
//                }, 'whenClient' => "function (attribute, value) { 
//              return $('#tblproductrequisitionsearch-vendor_type').val() == 'DCS'; 
//          }", 'on' => 'searchdispatch'],
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
        $query->joinWith(['dcsCode', 'customerType', 'mainCustomerCode', 'bmcCode', 'mccCode', 'plantCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_requisition', 'tbl_mcc_plant', 'tbl_product_requisition');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (Yii::$app->session->get('BMC') !== '') {
            $query->andWhere(['tbl_product_requisition.bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        if (Yii::$app->session->get('Dcs') !== '') {
            $query->andWhere(['or', ['tbl_product_requisition.vendor_code' => explode(',', Yii::$app->session->get('Dcs'))], ['vendor_type' => 'BMC']]);
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
        $query->andFilterWhere(['or',
                ['like', 'tbl_dcs.dcs_name', $this->customer_name],
                ['like', 'tbl_bmc.bmc_name', $this->customer_name]
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product_requisition.status' => $this->status,
        ]);
        $query->andFilterWhere(['like', 'tbl_product_requisition.vendor_code', $this->vendor_code])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->vendor_type])
                ->andFilterWhere(['like', 'tbl_plant.name', $this->plant_name])
                ->andFilterWhere(['like', 'tbl_mcc_plant.name', $this->mcc_name])
                ->andFilterWhere(['like', 'tbl_product_requisition.description', $this->description]);

        return $dataProvider;
    }

    public function searchDispatchRequisition($params) {
        $query = TblProductRequisition::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);


        $query->joinWith(['dcsCode']);

        $this->load($params);
//        $query->andWhere([
//            'tbl_product_requisition.union_code' => $this->union_code,
//            'tbl_product_requisition.plant_code' => $this->plant_code,
//            'tbl_product_requisition.mcc_plant_code' => $this->mcc_plant_code,
//            'tbl_product_requisition.bmc_code' => $this->bmc_code,
//            'tbl_product_requisition.vendor_type' => $this->vendor_type,
//        ]);
//        $query->andWhere(['tbl_product_requisition.status' => ['Under Dispatch']]);

        if (strtolower($this->vendor_type) == 'dcs') {
            $query->andFilterWhere([
                'tbl_product_requisition.dcs_code' => $this->dcs_code,
                'tbl_dcs.route_code' => $this->route_code
            ]);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (Yii::$app->session->get('Dcs') !== '') {
            $query->andWhere(['tbl_product_requisition.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        if (Yii::$app->session->get('BMC') !== '') {
            $query->andWhere(['tbl_product_requisition.bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        if (Yii::$app->session->get('MCC') !== '') {
            $query->andWhere(['tbl_product_requisition.mcc_plant_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        // $query->andWhere('tbl_product_requisition.status="2" OR tbl_product_requisition.status="6" OR tbl_product_requisition.status="7"');
        return $dataProvider;
    }

}
