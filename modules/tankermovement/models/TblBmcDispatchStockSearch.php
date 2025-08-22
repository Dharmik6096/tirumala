<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblBmcDispatchStock;

/**
 * TblBmcDispatchStockSearch represents the model behind the search form about `app\modules\tankermovement\models\TblBmcDispatchStock`.
 */
class TblBmcDispatchStockSearch extends TblBmcDispatchStock {

    public $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bmc_dispatch_stock_code', 'transaction_date', 'to_date', 'type', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'from_date', 'from_shift_code'], 'safe'],
                [['to_shift_code', 'qty_diff_type_code', 'milk_quality_type_code', 'milk_type_code', 'bmc_silos_info_code', 'originating_type'], 'safe'],
                [['opening_bal', 'closing_bal', 'purchase_qty', 'qty_diff', 'extra_qty', 'balance_qty', 'fat', 'snf', 'water', 'from_shift', 'to_shift'], 'safe'],
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
        $query = TblBmcDispatchStock::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'fromShiftCode', 'toShiftCode', 'milkType', 'milkQualityType', 'silosInfoCode', 'qtyDiffType']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_bmc_dispatch_stock', 'tbl_bmc_dispatch_stock', 'tbl_bmc_dispatch_stock');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (empty($this->from_date)) {
            $this->from_date = date('Y-m-d');
        }
        if (empty($this->to_date)) {
            $this->to_date = date('Y-m-d');
        }
        $from_date = date('Y-m-d', strtotime($this->from_date));
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andWhere(['>=', 'tbl_bmc_dispatch_stock.transaction_date', $from_date]);

        $to_date = date('Y-m-d', strtotime($this->to_date));
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andWhere(['<=', 'tbl_bmc_dispatch_stock.transaction_date', $to_date]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_bmc_dispatch_stock.milk_quality_type_code' => $this->milk_quality_type_code,
            'tbl_bmc_dispatch_stock.milk_type_code' => $this->milk_type_code,
        ]);

        $query->andFilterWhere(['like', 'tbl_bmc_dispatch_stock.bmc_dispatch_stock_code', $this->bmc_dispatch_stock_code])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_stock.type', $this->type])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_stock.remarks', $this->remarks])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_stock.fat', $this->fat])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_stock.snf', $this->snf])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_stock.opening_bal', $this->opening_bal])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_stock.balance_qty', $this->balance_qty])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_stock.purchase_qty', $this->purchase_qty])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_stock.qty_diff', $this->qty_diff])
                ->andFilterWhere(['like', 'tbl_bmc_silos_info.silo_no', $this->bmc_silos_info_code])
                ->andFilterWhere(['like', 'tbl_bmc_dispatch_stock.remarks', $this->remarks])
                ->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code]);
        return $dataProvider;
    }

    public function searchBmcStockDetail($params) {
        $query = TblBmcDispatchStock::find()->where(['union_code' => $this->union_code, 'plant_code' => $this->plant_code, 'mcc_plant_code' => $this->mcc_plant_code, 'bmc_code' => $this->bmc_code, 'to_date' => $this->to_date, 'to_shift_code' => $this->to_shift_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }

}
