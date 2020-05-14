<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblVehicleTrip;

/**
 * TblVehicleTripSearch represents the model behind the search form about `app\modules\tankermovement\models\TblVehicleTrip`.
 */
class TblVehicleTripSearch extends TblVehicleTrip {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vehicle_trip_code', 'vehicle_code', 'trip_code', 'grn_no', 'transaction_date', 'trip_status', 'trip_for', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'trip_mode'], 'safe'],
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
        $query = TblVehicleTrip::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['vehicleCode', 'vehicleCode.transporter']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_vehicle_trip', 'tbl_vehicle_trip', 'tbl_vehicle_trip');

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->transaction_date));
            $query->andFilterWhere(['>=', 'tbl_vehicle_trip.transaction_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->transaction_date));
            $query->andFilterWhere(['<=', 'tbl_vehicle_trip.transaction_date', $to_date]);
        }
        $query->andFilterWhere(['=', 'tbl_vehicle_trip.transaction_date', !empty($this->transaction_date) ? date('Y-m-d', strtotime($this->transaction_date)) : NULL]);

        $query->andFilterWhere(['like', 'trip_code', $this->trip_code])
                ->andFilterWhere(['like', 'grn_no', $this->grn_no])
                ->andFilterWhere(['like', 'trip_status', $this->trip_status])
                ->andFilterWhere(['like', 'trip_mode', $this->trip_mode]);

        return $dataProvider;
    }

}
