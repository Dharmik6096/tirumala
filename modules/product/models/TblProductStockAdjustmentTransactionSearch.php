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
            [['old_value', 'new_value', 'final_value', 'stock', 'qty'], 'number'],
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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'product_stock_adjustment_transaction_code' => $this->product_stock_adjustment_transaction_code,
            'old_value' => $this->old_value,
            'new_value' => $this->new_value,
            'final_value' => $this->final_value,
            'transaction_date' => $this->transaction_date,
            'stock' => $this->stock,
            'qty' => $this->qty,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'product_stock_adjustment_code', $this->product_stock_adjustment_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'product_code', $this->product_code])
            ->andFilterWhere(['like', 'sap_batch_no', $this->sap_batch_no])
            ->andFilterWhere(['like', 'adjustment_type', $this->adjustment_type])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
            ->andFilterWhere(['like', 'x_col1', $this->x_col1])
            ->andFilterWhere(['like', 'x_col2', $this->x_col2])
            ->andFilterWhere(['like', 'x_col3', $this->x_col3])
            ->andFilterWhere(['like', 'x_col4', $this->x_col4])
            ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }
}
