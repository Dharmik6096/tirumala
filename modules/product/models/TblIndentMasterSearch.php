<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblIndentMaster;
use yii\data\ArrayDataProvider;

/**
 * TblIndentMasterSearch represents the model behind the search form about `app\modules\product\models\TblIndentMaster`.
 */
class TblIndentMasterSearch extends TblIndentMaster {

    public $from_date, $to_date, $product_group_code, $group_by, $payment_cycle_code, $bmc_name, $dcs_name, $member_name, $customer_name, $sap_farmer_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['indent_code', 'customer_type', 'customer_code', 'member_code', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'indent_date', 'product_code', 'status', 'status_date', 'status_by', 'status_remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'route_code', 'approve_qty', 'rejected_qty', 'approve_remarks', 'received_qty', 'dispatch_qty', 'is_close', 'bmc_name', 'dcs_name', 'member_name', 'customer_name', 'sap_farmer_code'], 'safe'],
            [['qty'], 'number'],
            [['originating_type'], 'integer'],
            [['indent_type', 'warehouse_code', 'product_group_code'], 'safe'],
            [['from_date', 'to_date', 'group_by', 'payment_cycle_code'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_type'], 'required', 'on' => ['indentApprove', 'indentApproveNew']],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required', 'on' => ['indentApprove', 'indentApproveNew']],
            [['payment_cycle_code'], 'required', 'on' => ['indentApproveNew']],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'route_code'], 'required', 'on' => 'searchdispatch'],
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
        $query = TblIndentMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['memberCode', 'productCode', 'dcsCode', 'bmcCode', 'customerCode']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_indent_master', 'tbl_indent_master', 'tbl_indent_master');

        // grid filtering conditions
        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'indent_date', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'indent_date', $to_date]);

        if (!empty($this->indent_date)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), indent_date, 126)', date('Y-m-d', strtotime($this->indent_date))]);
        }

        $query->andFilterWhere(['like', 'indent_code', $this->indent_code])
                ->andFilterWhere(['like', 'tbl_member.ex_member_code', $this->member_code])
                ->andFilterWhere(['like', 'tbl_member.member_name', $this->member_name])
                ->andFilterWhere(['like', 'tbl_member.sap_farmer_code', $this->sap_farmer_code])
                ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code])
                ->andFilterWhere(['like', 'qty', $this->qty])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'status_by', $this->status_by])
                ->andFilterWhere(['like', 'received_qty', $this->received_qty])
                ->andFilterWhere(['like', 'approve_qty', $this->approve_qty])
                ->andFilterWhere(['like', 'rejected_qty', $this->rejected_qty])
                ->andFilterWhere(['like', 'dispatch_qty', $this->dispatch_qty])
                ->andFilterWhere(['like', 'tbl_dcs.ref_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', 'tbl_bmc.ref_code', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_name])
                ->andFilterWhere(['like', 'tbl_customer_master.ref_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_customer_master.customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'status_remarks', $this->status_remarks]);

        return $dataProvider;
    }

    public function createsearch($params) {
        $query = TblIndentMaster::find();

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
        $query->andWhere([
            'tbl_indent_master.bmc_code' => $this->bmc_code]);
        if (!empty($this->indent_date)) {
            $query->andFilterWhere(['CAST(indent_date as date)' => date('Y-m-d', strtotime($this->indent_date))]);
        }
        $query->andFilterWhere(['tbl_indent_master.indent_type' => $this->indent_type,
            'tbl_indent_master.warehouse_code' => $this->warehouse_code]);


        return $dataProvider;
    }

    public function indentapprovesearch($params, $sp = 'portal_sp_pending_indent_approval') {
        $this->load($params);
        $output = [];
        if (!empty($params)) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'customer_code' => '',
                'user_code' => \Yii::$app->user->identity->user_code,
                'from_date' => '',
                'to_date' => '',
                'customer_type' => '',
            ];

            $sp_params = array_merge($sp_params, $params['TblIndentMasterSearch']);
            if($sp_params['customer_type'] == 'DCS'){
                $sp_params['customer_code'] = $sp_params['dcs_code'];
            }
//            if (empty($this->bmc_code)) {
//                $this->bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
//            }
//            if (empty($this->mcc_plant_code)) {
//                $this->mcc_plant_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
//            }
            if (empty($this->dcs_code)) {
                $this->dcs_code = !empty(Yii::$app->session->get('DCS')) ? ',' . Yii::$app->session->get('DCS') . ',' : 0;
            }
            $sp_params['mcc_plant_code'] = $this->mcc_plant_code;
            $sp_params['bmc_code'] = $this->bmc_code;
            $sp_params['dcs_code'] = $this->dcs_code;
            $sp_params['user_code'] = Yii::$app->session->get('UserCode');
            $sp_params['from_date'] = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $sp_params['to_date'] = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            unset($sp_params['dcs_code']);
            unset($sp_params['group_by']);
            unset($sp_params['payment_cycle_code']);
            $output = \Yii::$app->general->getSpData($sp, $sp_params);
        }
        $dataProvider = new ArrayDataProvider();
        if (!empty($output)) {
            $attr = '';
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $output,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }
        //var_dump($output); exit;
        return $dataProvider;
    }

    public function searchDispatch($params) {
        $query = TblIndentMaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $query->joinWith(['dcsCode']);

        $this->load($params);
        $query->andWhere([
            'tbl_indent_master.union_code' => $this->union_code,
            'tbl_indent_master.plant_code' => $this->plant_code,
            'tbl_indent_master.mcc_plant_code' => $this->mcc_plant_code,
            'tbl_indent_master.bmc_code' => $this->bmc_code,
            'tbl_indent_master.customer_type' => $this->customer_type,
        ]);
        $query->andWhere(['tbl_indent_master.status' => ['2']]);

        $query->andFilterWhere([
            'tbl_indent_master.dcs_code' => $this->dcs_code,
            'tbl_dcs.route_code' => $this->route_code
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // $query->andWhere('tbl_product_requisition.status="2" OR tbl_product_requisition.status="6" OR tbl_product_requisition.status="7"');
        return $dataProvider;
    }

    public function indentdispatchsearch($params) {
        $query = TblIndentMaster::find()->select(['tbl_indent_master.union_code', 'tbl_indent_master.plant_code', 'tbl_indent_master.mcc_plant_code', 'tbl_indent_master.bmc_code', 'tbl_indent_master.dcs_code', 'tbl_indent_master.product_code', 'qty' => 'ISNULL(SUM(ISNULL(qty, 0)),0)']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $query->joinWith(['dcsCode']);

        $this->load($params);
        $query->andWhere([
            'tbl_indent_master.union_code' => $this->union_code,
            'tbl_indent_master.plant_code' => $this->plant_code,
            'tbl_indent_master.mcc_plant_code' => $this->mcc_plant_code,
            'tbl_indent_master.bmc_code' => $this->bmc_code,
        ]);
        $query->andWhere(['tbl_indent_master.status' => ['2']]);

        $query->andFilterWhere(['tbl_dcs.route_code' => $this->route_code,
            'tbl_indent_master.dcs_code' => $this->dcs_code]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->groupBy(['tbl_indent_master.union_code', 'tbl_indent_master.plant_code', 'tbl_indent_master.mcc_plant_code', 'tbl_indent_master.bmc_code', 'tbl_indent_master.dcs_code', 'tbl_indent_master.product_code']);
        // $query->andWhere('tbl_product_requisition.status="2" OR tbl_product_requisition.status="6" OR tbl_product_requisition.status="7"');
        return $dataProvider;
    }

    public function indentdispatchothersearch($params) {
        $query = TblIndentMaster::find()->select(['tbl_indent_master.indent_code', 'tbl_indent_master.union_code', 'tbl_indent_master.plant_code', 'tbl_indent_master.mcc_plant_code', 'tbl_indent_master.bmc_code', 'tbl_indent_master.dcs_code', 'tbl_indent_master.product_code', 'tbl_indent_master.warehouse_code', 'qty' => 'ISNULL(SUM(ISNULL(qty, 0)),0)', 'approve_qty' => 'ISNULL(SUM(ISNULL(approve_qty, 0)),0)', 'tbl_indent_master.status_date']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $query->joinWith(['dcsCode']);

        $this->load($params);
        $query->andWhere([
            'tbl_indent_master.union_code' => $this->union_code,
            'tbl_indent_master.plant_code' => $this->plant_code,
            'tbl_indent_master.mcc_plant_code' => $this->mcc_plant_code,
            'tbl_indent_master.bmc_code' => $this->bmc_code,
        ]);
        $query->andWhere(['tbl_indent_master.status' => ['2'], 'tbl_indent_master.is_close' => '0']);

        $query->andFilterWhere(['tbl_dcs.route_code' => $this->route_code,
            'tbl_indent_master.dcs_code' => $this->dcs_code]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->groupBy(['tbl_indent_master.union_code', 'tbl_indent_master.plant_code', 'tbl_indent_master.mcc_plant_code', 'tbl_indent_master.bmc_code', 'tbl_indent_master.dcs_code', 'tbl_indent_master.product_code', 'tbl_indent_master.warehouse_code', 'tbl_indent_master.indent_code', 'tbl_indent_master.status_date']);
        // $query->andWhere('tbl_product_requisition.status="2" OR tbl_product_requisition.status="6" OR tbl_product_requisition.status="7"');
        return $dataProvider;
    }

    public function indentdispatchothernewsearch($params) {
        $this->load($params);
        $customer_code = '';
        $code = '';
        if($this->customer_type == 'BULKVEN'){
            $this->dcs_code = '';
            $this->route_code = '';
            $customer_code = ['BULKVEN'];
            $code = 'customer_code';
        }
        if($this->customer_type == 'DCS'){
            $this->customer_code = '';
            $customer_code = ['DCS','MEMBER'];
            $code = 'member_code';
        }
        $select = ['tbl_indent_master.indent_code', 'tbl_indent_master.union_code', 'tbl_indent_master.plant_code', 'tbl_indent_master.mcc_plant_code', 'tbl_indent_master.bmc_code', 'tbl_indent_master.dcs_code', 'tbl_indent_master.product_code', 'tbl_indent_master.warehouse_code', 'qty' => 'ISNULL(SUM(ISNULL(qty, 0)),0)', 'approve_qty' => 'ISNULL(SUM(ISNULL(approve_qty, 0)),0)', 'tbl_indent_master.status_date', 'tbl_indent_master.is_close', 'tbl_indent_master.customer_type', 'tbl_indent_master.'.$code.' AS code'];
        $group_by = ['tbl_indent_master.union_code', 'tbl_indent_master.plant_code', 'tbl_indent_master.mcc_plant_code', 'tbl_indent_master.bmc_code', 'tbl_indent_master.dcs_code', 'tbl_indent_master.product_code', 'tbl_indent_master.warehouse_code', 'tbl_indent_master.indent_code', 'tbl_indent_master.status_date', 'tbl_indent_master.is_close', 'tbl_indent_master.customer_type', 'tbl_indent_master.'.$code];

        if (!empty($this->group_by)) {
            $select = ['tbl_indent_master.dcs_code', 'tbl_indent_master.product_code', 'tbl_indent_master.member_code', 'qty' => 'ISNULL(SUM(ISNULL(qty, 0)), 0)', 'approve_qty' => 'ISNULL(SUM(ISNULL(approve_qty, 0)), 0)'];
            $group_by = ['tbl_indent_master.dcs_code', 'tbl_indent_master.product_code', 'tbl_indent_master.member_code'];
        }
        $query = TblIndentMaster::find()->select($select);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // $query->leftJoin('tbl_dcs', 'tbl_indent_master.dcs_code = tbl_dcs.dcs_code');
        $query->joinWith(['dcsCode']);

        $query->andWhere([
            'tbl_indent_master.union_code' => $this->union_code,
            'tbl_indent_master.plant_code' => $this->plant_code,
            'tbl_indent_master.mcc_plant_code' => $this->mcc_plant_code,
            'tbl_indent_master.bmc_code' => $this->bmc_code,
            'tbl_indent_master.status' => '2',
            'tbl_indent_master.is_close' => '0'
        ]);

        if (!empty($this->product_code)) {
            $query->andWhere(['tbl_indent_master.product_code' => $this->product_code]);
        }

        $query->andFilterWhere([
            'tbl_dcs.route_code' => $this->route_code,
            'tbl_indent_master.dcs_code' => $this->dcs_code,
            'tbl_indent_master.customer_code' => $this->customer_code
        ]);

        if(!empty($customer_code)){
            $query->andFilterWhere(['tbl_indent_master.customer_type' => $customer_code]);
        }

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->groupBy($group_by);

        return $dataProvider;
    }

}
