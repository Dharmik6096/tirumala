<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductStockAdjustmentTransaction;

/**
 * TblProductStockAdjustmentTransactionSearch represents the model behind the search form about `app\modules\product\models\TblProductStockAdjustmentTransaction`.
 */
class TblProductStockAdjustmentTransactionSearch extends TblProductStockAdjustmentTransaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_stock_adjustment_transaction_code', 'originating_type'], 'integer'],
            [['product_stock_adjustment_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'product_code', 'sap_batch_no', 'adjustment_type', 'transaction_date', 'unit', 'reason', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['stock', 'qty'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblProductStockAdjustmentTransaction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if(!empty($this->product_stock_adjustment_code)){
            $query->where(['tbl_product_stock_adjustment_transaction.product_stock_adjustment_code' => $this->product_stock_adjustment_code]);
        }
        
        $this->load($params);

        $query->joinWith(['unionCode', 'productCode', 'productStockAsjustmentCode']);
        
        Yii::$app->general->filterByOrg($query, $this);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->transaction_date)) {
            $transaction_date = !empty($this->transaction_date) ? date('Y-m-d', strtotime($this->transaction_date)) : date('Y-m-d');
            $query->andFilterWhere(['tbl_product_stock_adjustment_transaction.transaction_date' => $transaction_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product_stock_adjustment_transaction.product_stock_adjustment_transaction_code' => $this->product_stock_adjustment_transaction_code,
            'tbl_product_stock_adjustment_transaction.stock' => $this->stock,
            'tbl_product_stock_adjustment_transaction.qty' => $this->qty,
            'tbl_product_stock_adjustment_transaction.created_at' => $this->created_at,
            'tbl_product_stock_adjustment_transaction.updated_at' => $this->updated_at,
            'tbl_product_stock_adjustment_transaction.originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code])
            ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.sap_batch_no', $this->sap_batch_no])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.adjustment_type', $this->adjustment_type])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.unit', $this->unit])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.reason', $this->reason])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.remarks', $this->remarks])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.created_by', $this->created_by])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.originating_org_type', $this->originating_org_type])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.x_col1', $this->x_col1])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.x_col2', $this->x_col2])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.x_col3', $this->x_col3])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.x_col4', $this->x_col4])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment_transaction.x_col5', $this->x_col5]);

        return $dataProvider;
    }
}
