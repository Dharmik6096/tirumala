<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblDcsMilkDispatchTxn;

/**
 * TblDcsMilkDispatchTxnSearch represents the model behind the search form about `app\modules\collection\models\TblDcsMilkDispatchTxn`.
 */
class TblDcsMilkDispatchTxnSearch extends TblDcsMilkDispatchTxn {

    public $destination_type, $challan_no, $destination_code, $antibiotic, $vehicle_no;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['shift_code', 'dispatch_type', 'destination_type', 'challan_no', 'destination_code'], 'safe'],
            [['dcs_milk_dispatch_txn_code', 'dcs_milk_dispatch_code', 'milk_quality_type_code', 'milk_type_code', 'nos_of_can', 'converted_qty_mode'], 'integer'],
            [['dispatch_qty', 'qty_mode', 'converted_qty', 'avg_fat', 'avg_snf', 'avg_clr', 'water', 'temperature', 'total_amount'], 'number'],
            [['dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'ref_code', 'date_time_of_dispatch', 'antibiotic', 'vehicle_no'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['deleteMilkDispatch']],
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
        $query = TblDcsMilkDispatchTxn::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsMilkDispatch', 'dcsMilkDispatch.dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs_milk_dispatch', 'tbl_dcs_milk_dispatch', 'tbl_dcs_milk_dispatch');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_dispatch', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_dispatch', $to_date]);
        }
        if (!empty($this->date_time_of_dispatch))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_dispatch, 126)', date('Y-m-d', strtotime($this->date_time_of_dispatch))]);

        $query->andFilterWhere([
            'tbl_dcs_milk_dispatch.shift_code' => $this->shift_code,
            'tbl_dcs_milk_dispatch.dispatch_type' => $this->dispatch_type,
            'tbl_dcs_milk_dispatch.destination_type' => $this->destination_type,
            'milk_quality_type_code' => $this->milk_quality_type_code,
            'milk_type_code' => $this->milk_type_code,
            'nos_of_can' => $this->nos_of_can,
            'dispatch_qty' => $this->dispatch_qty,
            'qty_mode' => $this->qty_mode,
            'converted_qty' => $this->converted_qty,
            'converted_qty_mode' => $this->converted_qty_mode,
            'avg_fat' => $this->avg_fat,
            'avg_snf' => $this->avg_snf,
            'avg_clr' => $this->avg_clr,
            'water' => $this->water,
            'total_amount' => $this->total_amount,
            'tbl_dcs_milk_dispatch.antibiotic' => $this->antibiotic,
        ]);

        $query->andFilterWhere(['like', 'tbl_dcs_milk_dispatch.challan_no', $this->challan_no])
                ->andFilterWhere(['like', 'tbl_dcs.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_dcs_milk_dispatch.destination_code', $this->destination_code])
                ->andFilterWhere(['like', 'tbl_dcs_milk_dispatch.vehicle_no', $this->vehicle_no]);

        $query->orderBy(['tbl_dcs_milk_dispatch.date_time_of_dispatch' => SORT_DESC,
            'tbl_dcs_milk_dispatch.bmc_code' => SORT_ASC,
            'tbl_dcs_milk_dispatch.dcs_code' => SORT_ASC]);

        return $dataProvider;
    }

    public function updatesarch($params) {
        $this->load($params);
        $query = TblDcsMilkDispatchTxn::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        $query->joinWith(['dcsMilkDispatch']);
        $query->andWhere([
            'tbl_dcs_milk_dispatch.bmc_code' => $this->bmc_code]);

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_dispatch', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift($this->to_shift);
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_dispatch', $to_date]);
        }

        $query->andFilterWhere(['tbl_dcs_milk_dispatch_txn.dcs_code' => $this->dcs_code]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function deletesearch($params) {
        $this->load($params);
        $query = TblDcsMilkDispatchTxn::find();
        // add conditions that should always apply here
//        $query->joinWith(['dcsMilkDispatch', 'approvalData']);
        $query->joinWith(['dcsMilkDispatch']);
        $query->join('LEFT JOIN', 'tbl_collection_data_alias', "tbl_collection_data_alias.dcs_code = tbl_dcs_milk_dispatch_txn.dcs_code and tbl_collection_data_alias.old_milk_type_code = tbl_dcs_milk_dispatch_txn.milk_type_code and tbl_collection_data_alias.old_milk_quality_type_code = tbl_dcs_milk_dispatch_txn.milk_quality_type_code and tbl_collection_data_alias.table_name = 'tbl_dcs_milk_dispatch' and tbl_collection_data_alias.action_perform = 'DELETE'");
        $query->join('LEFT JOIN', 'tbl_dcs_milk_dispatch dmd', 'dmd.date_time_of_dispatch = tbl_collection_data_alias.date_time_of_collection and dmd.shift_code = tbl_collection_data_alias.shift_code');
        $query->andWhere([
            'tbl_dcs_milk_dispatch.mcc_plant_code' => $this->mcc_plant_code]);
        if (empty($this->from_date)) {
            $this->from_date = date('Y-m-d');
        }
        if (empty($this->to_date)) {
            $this->to_date = date('Y-m-d');
        }
        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'tbl_dcs_milk_dispatch.date_time_of_dispatch', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift($this->to_shift);
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'tbl_dcs_milk_dispatch.date_time_of_dispatch', $to_date]);
        }

        $query->andFilterWhere(['tbl_dcs_milk_dispatch.dcs_code' => $this->dcs_code]);
        $query->andFilterWhere(['tbl_dcs_milk_dispatch.bmc_code' => $this->bmc_code]);

        $query->andWhere(['or', ['is', 'tbl_collection_data_alias.bmc_code', NULL], ['is', 'tbl_collection_data_alias.shift_code', NULL], ['is', 'tbl_collection_data_alias.dcs_code', NULL], ['is', 'tbl_collection_data_alias.milk_type_code', NULL], ['is', 'tbl_collection_data_alias.milk_quality_type_code', NULL], ['is', 'tbl_collection_data_alias.date_time_of_collection', NULL]]);
//        $query->andwhere(['tbl_collection_data_alias.action_perform' => 'DELETE', 'tbl_collection_data_alias.table_name' => 'collectionvillage']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->andFilterWhere(['tbl_dcs_milk_dispatch.dcs_code' => $this->dcs_code]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

}
