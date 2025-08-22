<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblBmcDispatchFlushStock;

/**
 * TblBmcDispatchFlushStockSearch represents the model behind the search form about `app\modules\tankermovement\models\TblBmcDispatchFlushStock`.
 */
class TblBmcDispatchFlushStockSearch extends TblBmcDispatchFlushStock {

    public $from_date, $from_shift, $to_date, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bmc_dispatch_flush_stock_code', 'shift_code', 'bmc_silos_info_code', 'milk_type_code', 'milk_quality_type_code', 'originating_type', 'qty', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'transaction_date', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
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
        $query = TblBmcDispatchFlushStock::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'shiftCode', 'milkType', 'milkQualityType', 'silosInfoCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_bmc_dispatch_flush_stock', 'tbl_bmc_dispatch_flush_stock', 'tbl_bmc_dispatch_flush_stock');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'tbl_bmc_dispatch_flush_stock.transaction_date', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'tbl_bmc_dispatch_flush_stock.transaction_date', $to_date]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_bmc_dispatch_flush_stock.milk_type_code' => $this->milk_type_code,
            'tbl_bmc_dispatch_flush_stock.milk_quality_type_code' => $this->milk_quality_type_code,
        ]);

        $query->andFilterWhere(['like', 'tbl_bmc_dispatch_flush_stock.remarks', $this->remarks])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_flush_stock.bmc_silos_info_code', $this->bmc_silos_info_code])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_flush_stock.qty', $this->qty])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code]);


        return $dataProvider;
    }

}
