<?php

namespace app\modules\product\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductStockSap;

class TblProductStockSapSearch extends TblProductStockSap {

    public $f_plant_code, $f_mcc_code, $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_code', 'qty', 'stock_date', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['f_plant_code', 'f_mcc_code', 'from_date', 'to_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
        $query = TblProductStockSap::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_stock_sap','tbl_product_stock_sap','tbl_product_stock_sap','tbl_product_stock_sap');

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(stock_date as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(stock_date as date)', $to_date]);
        }
        $query->andFilterWhere(['like', 'product_code', $this->product_code]);

        return $dataProvider;
    }

}
