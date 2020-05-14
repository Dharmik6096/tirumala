<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblVehicleTripDetail;

/**
 * TblVehicleTripDetailSearch represents the model behind the search form about `app\modules\tankermovement\models\TblVehicleTripDetail`.
 */
class TblVehicleTripDetailSearch extends TblVehicleTripDetail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vehicle_trip_detail_code', 'vehicle_trip_code', 'vehicle_code', 'trip_code', 'challan_no', 'transaction_datetime', 'destination_code', 'destination_type', 'source_org_code', 'source_org_type', 'arrival_time', 'departure_time', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['is_last_destination', 'originating_type'], 'integer'],
            [['travel_km'], 'number'],
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
        $query = TblVehicleTripDetail::find()->where(['vehicle_trip_code' => $this->vehicle_trip_code]);
        $query->orderBy(['transaction_datetime' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }

}
