<?php

namespace app\modules\product\models;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductStockPhysical;

/**
 * TblProductStockPhysicalSearch represents the model behind the search form about `app\models\TblProductStockPhysical`.
 */
class TblProductStockPhysicalSearch extends TblProductStockPhysical {

    public $f_plant_code, $f_mcc_code, $f_bmc_code, $f_dcs_code, $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_code', 'qty', 'stock_date', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code', 'from_date', 'to_date'], 'safe'],
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
        $query = TblProductStockPhysical::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_product_stock_physical','tbl_product_stock_physical','tbl_product_stock_physical','tbl_product_stock_physical');

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
