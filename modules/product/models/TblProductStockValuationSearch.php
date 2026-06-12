<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductStockValuation;

/**
 * TblProductStockValuationSearch
 */
    class TblProductStockValuationSearch extends TblProductStockValuation
{
    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_stock_valuation_code', 'union_code', 'product_code', 'product_name', 'stock', 'valuation', 'unit', 'generated_at', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_active', 'x_col1', 'x_col2', 'x_col3', 'from_date', 'to_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = TblProductStockValuation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['productCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_stock_valuation', 'tbl_product_stock_valuation');

        $query->andFilterWhere([
            'tbl_product_stock_valuation.is_active' => $this->is_active,
            'tbl_product_stock_valuation.stock' => $this->stock,
            'tbl_product_stock_valuation.valuation' => $this->valuation,
        ]);

        $query->andFilterWhere(['like', 'tbl_product_stock_valuation.product_stock_valuation_code', $this->product_stock_valuation_code])
            ->andFilterWhere(['like', 'tbl_product_stock_valuation.product_code', $this->product_code])
            ->andFilterWhere(['like', 'tbl_product_stock_valuation.product_name', $this->product_name])
            ->andFilterWhere(['like', 'tbl_product_stock_valuation.unit', $this->unit]);

        if (!empty($this->from_date)) {
            $query->andFilterWhere(['>=', 'tbl_product_stock_valuation.generated_at', date('Y-m-d', strtotime($this->from_date))]);
        }
        if (!empty($this->to_date)) {
            $query->andFilterWhere(['<=', 'tbl_product_stock_valuation.generated_at', date('Y-m-d', strtotime($this->to_date))]);
        }

        return $dataProvider;
    }
}
