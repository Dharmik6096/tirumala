<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductDispatchTransaction;

/**
 * TblProductDispatchTransactionSearch represents the model behind the search form about `app\modules\product\models\TblProductDispatchTransaction`.
 */
class TblProductDispatchTransactionSearch extends TblProductDispatchTransaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dispatch_transaction_code', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'challan_no', 'dispatch_date', 'product_requisition_code', 'requisition_transaction_code', 'product_code', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['rate', 'amount', 'discount_amount', 'dispatch_qty'], 'number'],
            [['originating_type'], 'integer'],
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
        $query = TblProductDispatchTransaction::find();

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
            'dispatch_date' => $this->dispatch_date,
            'rate' => $this->rate,
            'amount' => $this->amount,
            'discount_amount' => $this->discount_amount,
            'dispatch_qty' => $this->dispatch_qty,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'dispatch_transaction_code', $this->dispatch_transaction_code])
            ->andFilterWhere(['like', 'vendor_type', $this->vendor_type])
            ->andFilterWhere(['like', 'vendor_code', $this->vendor_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'challan_no', $this->challan_no])
            ->andFilterWhere(['like', 'product_requisition_code', $this->product_requisition_code])
            ->andFilterWhere(['like', 'requisition_transaction_code', $this->requisition_transaction_code])
            ->andFilterWhere(['like', 'product_code', $this->product_code])
            ->andFilterWhere(['like', 'status', $this->status])
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
