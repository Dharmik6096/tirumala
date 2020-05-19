<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblBmcDispatchStock;

/**
 * TblBmcDispatchStockSearch represents the model behind the search form about `app\modules\tankermovement\models\TblBmcDispatchStock`.
 */
class TblBmcDispatchStockSearch extends TblBmcDispatchStock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bmc_dispatch_stock_code', 'transaction_date', 'to_date', 'type', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['to_shift_code', 'qty_diff_type_code', 'milk_quality_type_code', 'milk_type_code', 'bmc_silos_info_code', 'originating_type'], 'integer'],
            [['opening_bal', 'closing_bal', 'purchase_qty', 'qty_diff', 'extra_qty', 'balance_qty', 'fat', 'snf', 'water'], 'number'],
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
        $query = TblBmcDispatchStock::find();

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
            'transaction_date' => $this->transaction_date,
            'to_date' => $this->to_date,
            'to_shift_code' => $this->to_shift_code,
            'qty_diff_type_code' => $this->qty_diff_type_code,
            'milk_quality_type_code' => $this->milk_quality_type_code,
            'milk_type_code' => $this->milk_type_code,
            'bmc_silos_info_code' => $this->bmc_silos_info_code,
            'opening_bal' => $this->opening_bal,
            'closing_bal' => $this->closing_bal,
            'purchase_qty' => $this->purchase_qty,
            'qty_diff' => $this->qty_diff,
            'extra_qty' => $this->extra_qty,
            'balance_qty' => $this->balance_qty,
            'fat' => $this->fat,
            'snf' => $this->snf,
            'water' => $this->water,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'bmc_dispatch_stock_code', $this->bmc_dispatch_stock_code])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
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
