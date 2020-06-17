<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblMilkVehicleEntry;

/**
 * TblMilkVehicleEntrySearch represents the model behind the search form about `app\modules\tankermovement\models\TblMilkVehicleEntry`.
 */
class TblMilkVehicleEntrySearch extends TblMilkVehicleEntry {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_date', 'to_date', 'milk_vehicle_entry_code', 'trip_code', 'grn_no', 'receipt_at', 'vehicle_entry_date', 'vehicle_code', 'arrival_time', 'tare_weight_time', 'qty', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_code', 'customer_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'customer_name'], 'safe'],
            [['gross_weight', 'tare_weight'], 'number'],
            [['originating_type'], 'integer'],
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
        $query = TblMilkVehicleEntry::find();

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
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'customerType']);
        // grid filtering conditions

        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_vehicle_entry', 'tbl_milk_vehicle_entry', 'tbl_milk_vehicle_entry');

        $query->andFilterWhere([
            'receipt_at' => $this->receipt_at,
        ]);
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);
        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'CAST(vehicle_entry_date as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'CAST(vehicle_entry_date as date)', $to_date]);
        }
        $query->andFilterWhere(['like', 'milk_vehicle_entry_code', $this->milk_vehicle_entry_code])
                ->andFilterWhere(['like', 'trip_code', $this->trip_code])
                ->andFilterWhere(['like', 'grn_no', $this->grn_no])
                ->andFilterWhere(['like', 'receipt_at', $this->receipt_at])
                ->andFilterWhere(['like', 'vehicle_code', $this->vehicle_code])
                ->andFilterWhere(['like', 'gross_weight', $this->gross_weight])
                ->andFilterWhere(['like', 'tare_weight', $this->tare_weight])
                ->andFilterWhere(['like', 'qty', $this->qty])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_bmc_collection.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'vehicle_entry_date', (!empty($this->vehicle_entry_date)) ? date('Y-m-d', strtotime($this->vehicle_entry_date)) : '']);


        return $dataProvider;
    }

}
