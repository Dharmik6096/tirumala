<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductStockAdjustment;

/**
 * TblProductStockAdjustmentSearch represents the model behind the search form about `app\modules\product\models\TblProductStockAdjustment`.
 */
class TblProductStockAdjustmentSearch extends TblProductStockAdjustment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_stock_adjustment_code', 'originating_type'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'product_code', 'sap_batch_no', 'adjustment_type', 'invoice_no', 'transaction_date', 'unit', 'reason', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
    public function search($params, $adjustment_type = '')
    {
        $query = TblProductStockAdjustment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
        if($adjustment_type){
            $query->where(['tbl_product_stock_adjustment.adjustment_type' => $adjustment_type]);
        }
        
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode', 'productStockAsjustmentTransactionCode']);
        
        Yii::$app->general->filterByOrg($query, $this);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if (!empty($this->transaction_date)) {
            $transaction_date = !empty($this->transaction_date) ? date('Y-m-d', strtotime($this->transaction_date)) : date('Y-m-d');
            $query->andFilterWhere(['tbl_product_stock_adjustment.transaction_date' => $transaction_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product_stock_adjustment.product_stock_adjustment_code' => $this->product_stock_adjustment_code,
            'tbl_product_stock_adjustment.created_at' => $this->created_at,
            'tbl_product_stock_adjustment.updated_at' => $this->updated_at,
            'tbl_product_stock_adjustment.originating_type' => $this->originating_type,
        ]);
        
        $query->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code])
            ->andFilterWhere(['like', 'tbl_plant.name', $this->plant_code])
            ->andFilterWhere(['like', 'tbl_mcc_plant.name', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code])
            ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.adjustment_type', $this->adjustment_type])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.invoice_no', $this->invoice_no])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.remarks', $this->remarks])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.created_by', $this->created_by])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.originating_org_type', $this->originating_org_type])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.x_col1', $this->x_col1])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.x_col2', $this->x_col2])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.x_col3', $this->x_col3])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.x_col4', $this->x_col4])
            ->andFilterWhere(['like', 'tbl_product_stock_adjustment.x_col5', $this->x_col5]);

        return $dataProvider;
    }
    
    public function createsearch($params) {
        $query = TblProductStockTransaction::find();

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
//        $query->andWhere([
//            'product_code' => $this->product_code,
//        ]);
        return $dataProvider;
    }
}
