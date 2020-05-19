<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductReceiptTransaction;

/**
 * TblProductReceiptTransactionSearch represents the model behind the search form about `app\modules\product\models\TblProductReceiptTransaction`.
 */
class TblProductReceiptTransactionSearch extends TblProductReceiptTransaction {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_receipt_transaction_code', 'product_requisition_code', 'requisition_transaction_code', 'product_code', 'product_receipt_code', 'remark', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'uom'], 'safe'],
                [['requested_quantity', 'dispatched_quantity', 'received_quantity', 'rejected_quantity', 'rate', 'amount', 'discount'], 'number'],
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
        $query = TblProductReceiptTransaction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['productCode', 'productCode.unitCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product_receipt_transaction.requested_quantity' => $this->requested_quantity,
            'tbl_product_receipt_transaction.dispatched_quantity' => $this->dispatched_quantity,
            'tbl_product_receipt_transaction.received_quantity' => $this->received_quantity,
            'tbl_product_receipt_transaction.rejected_quantity' => $this->rejected_quantity,
            'tbl_product_receipt_transaction.rate' => $this->rate,
            'tbl_product_receipt_transaction.amount' => $this->amount,
            'tbl_product_receipt_transaction.discount' => $this->discount,
            'tbl_product_receipt_transaction.product_receipt_code' => $this->product_receipt_code,
        ]);

        $query->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code])
                ->andFilterWhere(['like', 'tbl_units.unit_name', $this->uom])
                ->andFilterWhere(['like', 'tbl_product_receipt_transaction.requisition_transaction_code', $this->requisition_transaction_code])
                ->andFilterWhere(['like', 'tbl_product_receipt_transaction.product_requisition_code', $this->product_requisition_code])
                ->andFilterWhere(['like', 'tbl_product_receipt_transaction.remark', $this->remark]);

        return $dataProvider;
    }

}
