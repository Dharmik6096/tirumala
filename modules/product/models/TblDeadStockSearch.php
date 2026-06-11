<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblDeadStock;

/**
 * TblDeadStockSearch
 */
class TblDeadStockSearch extends TblDeadStock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dead_stock_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'name', 'name_local', 'ledger_account', 'qty', 'amount', 'purchase_date', 'transaction_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'is_active'], 'safe'],
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
        $query = TblDeadStock::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        Yii::$app->general->filterByOrg($query, $this, 'tbl_dead_stock', 'tbl_dead_stock', 'tbl_dead_stock', 'tbl_dead_stock');

        $query->andFilterWhere([
            'tbl_dead_stock.is_active' => $this->is_active,
            'tbl_dead_stock.qty' => $this->qty,
            'tbl_dead_stock.amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'tbl_dead_stock.dead_stock_code', $this->dead_stock_code])
            ->andFilterWhere(['like', 'tbl_dead_stock.name', $this->name])
            ->andFilterWhere(['like', 'tbl_dead_stock.name_local', $this->name_local])
            ->andFilterWhere(['like', 'tbl_dead_stock.ledger_account', $this->ledger_account]);

        return $dataProvider;
    }
}
