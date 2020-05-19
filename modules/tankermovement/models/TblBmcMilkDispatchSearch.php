<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblBmcMilkDispatch;

/**
 * TblBmcMilkDispatchSearch represents the model behind the search form about `app\modules\tankermovement\models\TblBmcMilkDispatch`.
 */
class TblBmcMilkDispatchSearch extends TblBmcMilkDispatch {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_milk_dispatch_code', 'challan_no', 'transaction_date', 'from_date', 'to_date', 'destination_type', 'destination_code', 'vehicle_code', 'trip_code', 'driver_name', 'driver_contact_no', 'authorizer_name', 'vehicle_in_time', 'vehicle_out_time', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['from_shift_code', 'to_shift_code', 'is_last_destination', 'purchase_rate_code', 'originating_type'], 'integer'],
            [['gross_weight', 'tare_weight'], 'number'],
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
        $query = TblBmcMilkDispatch::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'transaction_date' => $this->transaction_date,
            'from_date' => $this->from_date,
            'from_shift_code' => $this->from_shift_code,
            'to_date' => $this->to_date,
            'to_shift_code' => $this->to_shift_code,
            'vehicle_in_time' => $this->vehicle_in_time,
            'vehicle_out_time' => $this->vehicle_out_time,
            'gross_weight' => $this->gross_weight,
            'tare_weight' => $this->tare_weight,
            'is_last_destination' => $this->is_last_destination,
            'purchase_rate_code' => $this->purchase_rate_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'bmc_milk_dispatch_code', $this->bmc_milk_dispatch_code])
                ->andFilterWhere(['like', 'challan_no', $this->challan_no])
                ->andFilterWhere(['like', 'destination_type', $this->destination_type])
                ->andFilterWhere(['like', 'destination_code', $this->destination_code])
                ->andFilterWhere(['like', 'vehicle_code', $this->vehicle_code])
                ->andFilterWhere(['like', 'trip_code', $this->trip_code])
                ->andFilterWhere(['like', 'driver_name', $this->driver_name])
                ->andFilterWhere(['like', 'driver_contact_no', $this->driver_contact_no])
                ->andFilterWhere(['like', 'authorizer_name', $this->authorizer_name])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }

}
