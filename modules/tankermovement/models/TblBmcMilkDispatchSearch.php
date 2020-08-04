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

    public $from_date, $to_date, $from_shift, $to_shift, $bmc_ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_milk_dispatch_code', 'challan_no', 'destination_type', 'destination_code', 'vehicle_code', 'trip_code', 'driver_name', 'driver_contact_no', 'authorizer_name', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'bmc_ref_code'], 'safe'],
            [['is_last_destination', 'transaction_date', 'originating_type'], 'integer'],
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
        $query->joinWith(['vehicleCode', 'vehicleCode.transporter', 'bmcCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_bmc_milk_dispatch', 'tbl_bmc_milk_dispatch', 'tbl_bmc_milk_dispatch');

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'tbl_bmc_milk_dispatch.from_date', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $from_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $from_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'tbl_bmc_milk_dispatch.to_date', $from_date]);
        }

        $query->andFilterWhere(['=', 'tbl_bmc_milk_dispatch.transaction_date', !empty($this->transaction_date) ? date('Y-m-d', strtotime($this->transaction_date)) : NULL]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_bmc_milk_dispatch.gross_weight' => $this->gross_weight,
            'tbl_bmc_milk_dispatch.tare_weight' => $this->tare_weight,
            'tbl_bmc_milk_dispatch.vehicle_code' => $this->vehicle_code,
            'tbl_vehicle_master.transporter_code' => $this->transporter_code
        ]);

        $query->andFilterWhere(['like', 'tbl_bmc_milk_dispatch.challan_no', $this->challan_no])
                ->andFilterWhere(['like', 'tbl_bmc_milk_dispatch.destination_type', $this->destination_type])
                ->andFilterWhere(['like', 'tbl_bmc_milk_dispatch.destination_code', $this->destination_code])
                ->andFilterWhere(['like', 'tbl_bmc_milk_dispatch.trip_code', $this->trip_code])
                ->andFilterWhere(['like', 'tbl_bmc_milk_dispatch.driver_name', $this->driver_name])
                ->andFilterWhere(['like', 'tbl_bmc_milk_dispatch.driver_contact_no', $this->driver_contact_no])
                ->andFilterWhere(['like', 'tbl_bmc_milk_dispatch.remarks', $this->remarks])
                ->andFilterWhere(['like', 'tbl_bmc.ref_code', $this->bmc_ref_code]);

        return $dataProvider;
    }

}
