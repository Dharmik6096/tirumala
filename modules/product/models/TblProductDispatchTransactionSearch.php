<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductDispatchTransaction;

/**
 * TblProductDispatchTransactionSearch represents the model behind the search form about `app\modules\product\models\TblProductDispatchTransaction`.
 */
class TblProductDispatchTransactionSearch extends TblProductDispatchTransaction {

    public $from_date, $to_date, $dispatch_center_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dispatch_transaction_code', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'challan_no', 'dispatch_date', 'product_requisition_code', 'requisition_transaction_code', 'product_code', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'from_date', 'to_date', 'dispatch_center_code'], 'safe'],
            [['rate', 'amount', 'discount_amount', 'dispatch_qty'], 'number'],
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
        $query = TblProductDispatchTransaction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['productCode', 'productDispatchCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_dispatch', 'tbl_product_dispatch', 'tbl_product_dispatch');
//        $query->andWhere(['challan_no' => $this->challan_no]);
        $productData = Yii::$app->general->getDispCenterProducts($this->dispatch_center_code);
        if ($productData['pass_where_close'] == 'Yes') {
            $query->andWhere(['tbl_product_dispatch_transaction.product_code' => $productData['product_list']]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_product_dispatch.dispatch_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_product_dispatch.dispatch_date as date)', $to_date]);
        }
        if (Yii::$app->session->get('Dcs') !== '') {
            $query->andWhere(['tbl_product_dispatch.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        if (Yii::$app->session->get('BMC') !== '') {
            $query->andWhere(['tbl_product_dispatch.bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product_dispatch_transaction.rate' => $this->rate,
            'tbl_product_dispatch_transaction.amount' => $this->amount,
            'tbl_product_dispatch_transaction.discount_amount' => $this->discount_amount,
            'tbl_product_dispatch_transaction.dispatch_qty' => $this->dispatch_qty,
        ]);

        $query->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code])
                ->andFilterWhere(['like', 'tbl_product_dispatch_transaction.challan_no', $this->challan_no])
                ->andFilterWhere(['like', 'tbl_product_dispatch_transaction.vendor_code', $this->vendor_code])
                ->andFilterWhere(['like', 'tbl_product_dispatch_transaction.dispatch_date', (!empty($this->dispatch_date)) ? date('Y-m-d', strtotime($this->dispatch_date)) : '']);

        return $dataProvider;
    }

}
