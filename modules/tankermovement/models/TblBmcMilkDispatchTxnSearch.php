<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxn;

/**
 * TblBmcMilkDispatchTxnSearch represents the model behind the search form about `app\modules\tankermovement\models\TblBmcMilkDispatchTxn`.
 */
class TblBmcMilkDispatchTxnSearch extends TblBmcMilkDispatchTxn {

    public $trip_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_milk_dispatch_txn_code', 'bmc_milk_dispatch_code', 'hsn_code', 'seal_no_top', 'seal_no_bottom', 'seal_no_broken', 'milk_analyser_type_code', 'ws_code', 'qty_time', 'qlty_time', 'adt_param', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'test_report_no', 'shift_of_milk'], 'safe'],
            [['milk_quality_type_code', 'milk_type_code', 'qty_diff_type_code', 'qty_mode', 'converted_qty_mode', 'bmc_silos_info_code', 'chamber_no', 'qty_auto', 'qlty_auto', 'is_rejected', 'originating_type'], 'integer'],
            [['dispatch_qty', 'qty_diff', 'balance_qty', 'converted_qty', 'fat', 'snf', 'clr', 'water', 'protein', 'density', 'lactose', 'rtpl', 'amount', 'freezing_point', 'temperature', 'dip_open', 'dip_close', 'dip_diff', 'adt_value'], 'number'],
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
        $query = TblBmcMilkDispatchTxn::find()->where(['bmc_milk_dispatch_code' => $this->bmc_milk_dispatch_code]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }

    public function searchTripDetail() {
        $query = TblBmcMilkDispatchTxn::find()->where(['bmc_milk_dispatch_code' => $this->bmc_milk_dispatch_code]);
        $query->joinWith(['bmcMilkDispatchCode']);
        $query->where(['tbl_bmc_milk_dispatch.trip_code' => $this->trip_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }

}
