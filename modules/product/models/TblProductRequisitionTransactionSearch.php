<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductRequisitionTransaction;

/**
 * TblProductRequisitionTransactionSearch represents the model behind the search form about `app\modules\product\models\TblProductRequisitionTransaction`.
 */
class TblProductRequisitionTransactionSearch extends TblProductRequisitionTransaction {

    public $from_date, $to_date, $vendor_code, $req_date, $description, $plant_name, $mcc_name, $dispatch_center_code, $remark, $union_code, $plant_code, $mcc_plant_code, $bmc_code, $vendor_type, $dcs_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['requisition_transaction_code', 'product_requisition_code', 'product_code', 'status', 'approved_by', 'approved_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'requisition_on_date', 'from_date', 'to_date', 'vendor_code', 'req_date', 'description', 'plant_name', 'mcc_name', 'dispatch_center_code', 'remark'], 'safe'],
            [['quantity', 'provisional_rate', 'provisional_amount', 'discount_amount', 'approved_quantity'], 'number'],
            [['is_approved', 'originating_type'], 'integer'],
            [['from_date', 'to_date'], 'required', 'on' => ['acceptRequisition']],
            [['union_code'], 'safe'],
            [['union_code'], 'required', 'on' => ['acceptRequisition']]
//        [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'vendor_type', 'dcs_code'], 'safe'],
//                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'vendor_type'], 'required', 'on' => ['acceptRequisition']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mcc_plant' => Yii::t('app', 'MCC Plant'),
            'bmc' => Yii::t('app', 'BMC'),
            'dcs' => Yii::t('app', 'DCS'),
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
        $query = TblProductRequisitionTransaction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['productCode', 'productRequisitionCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_requisition', 'tbl_product_requisition', 'tbl_product_requisition');
        $productData = Yii::$app->general->getDispCenterProducts($this->dispatch_center_code);
        if ($productData['pass_where_close'] == 'Yes') {
            $query->andWhere(['tbl_product_requisition_transaction.product_code' => $productData['product_list']]);
        }
//        $query->andFilterWhere(['product_requisition_code' => (string) $this->product_requisition_code]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_product_requisition.req_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_product_requisition.req_date as date)', $to_date]);
        }
        if (!empty($this->req_date)) {
            $req_date = date('Y-m-d', strtotime($this->req_date));
            $query->andFilterWhere(['like', 'cast(tbl_product_requisition.req_date as date)', $req_date]);
        }
        if (!empty($this->created_at)) {
            $created_at = date('Y-m-d', strtotime($this->created_at));
            $query->andFilterWhere(['like', 'cast(tbl_product_requisition.created_at as date)', $created_at]);
        }
        if (Yii::$app->session->get('Dcs') !== '') {
            $query->andWhere(['tbl_product_requisition.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        if (Yii::$app->session->get('BMC') !== '') {
            $query->andWhere(['tbl_product_requisition.bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product_requisition_transaction.quantity' => $this->quantity,
            'tbl_product_requisition_transaction.provisional_rate' => $this->provisional_rate,
            'tbl_product_requisition_transaction.provisional_amount' => $this->provisional_amount,
            'tbl_product_requisition_transaction.discount_amount' => $this->discount_amount,
            'tbl_product_requisition_transaction.is_approved' => $this->is_approved,
            'tbl_product_requisition_transaction.approved_quantity' => $this->approved_quantity,
            'tbl_product_requisition_transaction.approved_date' => $this->approved_date,
        ]);

        $query->andFilterWhere(['like', 'tbl_product_requisition_transaction.requisition_transaction_code', $this->requisition_transaction_code])
                ->andFilterWhere(['like', 'tbl_product_requisition_transaction.product_requisition_code', $this->product_requisition_code])
                ->andFilterWhere(['like', 'tbl_product_requisition_transaction.requisition_on_date', (!empty($this->requisition_on_date)) ? date('Y-m-d', strtotime($this->requisition_on_date)) : ''])
                ->andFilterWhere(['like', 'tbl_product_requisition_transaction.status', $this->status])
                ->andFilterWhere(['like', 'tbl_product_requisition_transaction.approved_by', $this->approved_by])
                ->andFilterWhere(['like', 'tbl_product_requisition.vendor_code', $this->vendor_code])
                ->andFilterWhere(['like', 'tbl_product_requisition.description', $this->description])
                ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code]);
        $query->orderBy('tbl_product_requisition_transaction.created_at DESC');
        return $dataProvider;
    }

    public function searchAcceptRequisition($params) {
        $query = TblProductRequisitionTransaction::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $productData = Yii::$app->general->getDispCenterProducts($this->dispatch_center_code);
        $query->joinWith(['productCode', 'productRequisitionCode']);

        if (empty($this->from_date)) {
            $this->from_date = date('d-m-Y');
        }
        if (empty($this->to_date)) {
            $this->to_date = date('d-m-Y');
        }
        $query->where(['tbl_product_requisition_transaction.status' => 'sent']);
        if ($productData['pass_where_close'] == 'Yes') {
            $query->andWhere(['tbl_product_requisition_transaction.product_code' => $productData['product_list']]);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
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

        $query->andWhere([
            'tbl_product_requisition.union_code' => $this->union_code,
//            'tbl_product_requisition.plant_code' => $this->plant_code,
//            'tbl_product_requisition.mcc_plant_code' => $this->mcc_plant_code,
//            'tbl_product_requisition.bmc_code' => $this->bmc_code,
//            'tbl_product_requisition.vendor_type' => $this->vendor_type,
        ]);
//        $query->andFilterWhere(['tbl_product_requisition.dcs_code' => $this->dcs_code]);
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_product_requisition.req_date as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_product_requisition.req_date as date)', $to_date]);
        }
        if (!empty($this->req_date)) {
            $query->andFilterWhere(['like', 'tbl_product_requisition.req_date', date('Y-m-d', strtotime($this->req_date))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product_requisition_transaction.quantity' => $this->quantity,
            'tbl_product_requisition_transaction.provisional_rate' => $this->provisional_rate,
            'tbl_product_requisition_transaction.provisional_amount' => $this->provisional_amount,
            'tbl_product_requisition_transaction.discount_amount' => $this->discount_amount,
            'tbl_product_requisition_transaction.is_approved' => $this->is_approved,
            'tbl_product_requisition_transaction.approved_quantity' => $this->approved_quantity,
            'tbl_product_requisition_transaction.approved_date' => $this->approved_date,
        ]);

        $query->andFilterWhere(['like', 'tbl_product_requisition_transaction.requisition_transaction_code', $this->requisition_transaction_code])
                ->andFilterWhere(['like', 'tbl_product_requisition_transaction.product_requisition_code', $this->product_requisition_code])
                ->andFilterWhere(['like', 'tbl_product_requisition_transaction.requisition_on_date', (!empty($this->requisition_on_date)) ? date('Y-m-d', strtotime($this->requisition_on_date)) : ''])
                ->andFilterWhere(['like', 'tbl_product_requisition_transaction.status', $this->status])
                ->andFilterWhere(['like', 'tbl_product_requisition_transaction.approved_by', $this->approved_by])
                ->andFilterWhere(['like', 'tbl_product_requisition.vendor_code', $this->vendor_code])
                ->andFilterWhere(['like', 'tbl_product_requisition.description', $this->description])
                ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code]);

        return $dataProvider;
    }

}
