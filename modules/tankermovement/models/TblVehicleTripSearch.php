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
        $query = TblVehicleTrip::find()->alias('t')->select(['t.vehicle_trip_code', 't.vehicle_code', 't.trip_code', 't.transaction_date', 't.grn_no', 't.trip_status',
            't.trip_mode', 't.union_code', 't.plant_code', 't.mcc_plant_code', 't.bmc_code',
            'challan_no' => "STUFF((
          SELECT ',' + d.challan_no
          FROM tbl_bmc_milk_dispatch d WHERE d.trip_code=t.trip_code
          FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, '')",
            'bmc_detail' => "STUFF((
          SELECT ',' + d.bmc_code
          FROM tbl_bmc_milk_dispatch d WHERE d.trip_code=t.trip_code
          FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, '')",
            'total_qty' => "SUM(tbl_bmc_milk_dispatch_txn.dispatch_qty)",
            'rejected_count' => "SUM(CASE WHEN tbl_bmc_milk_dispatch_txn.is_rejected=1 THEN 1 ELSE 0 END)",
            'kg_fat' => "sum({fn truncate (tbl_bmc_milk_dispatch_txn.dispatch_qty*tbl_bmc_milk_dispatch_txn.fat/100,2)})",
            'kg_snf' => "sum({fn truncate (tbl_bmc_milk_dispatch_txn.dispatch_qty*tbl_bmc_milk_dispatch_txn.snf/100,2)})",
        ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['vehicleCode', 'vehicleCode.transporter', 'bmcMilkDispatchCode', 'bmcMilkDispatchCode.bmcMilkDispatchTxnCode']);
        Yii::$app->general->filterByOrg($query, $this, 't', 't', 't');

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 't.transaction_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 't.transaction_date', $to_date]);
        }
        $query->andFilterWhere(['=', 't.transaction_date', !empty($this->transaction_date) ? date('Y-m-d', strtotime($this->transaction_date)) : NULL]);

        $query->andFilterWhere(['like', 'trip_code', $this->trip_code])
                ->andFilterWhere(['like', 'grn_no', $this->grn_no])
                ->andFilterWhere(['like', 'trip_status', $this->trip_status])
                ->andFilterWhere(['like', 'trip_mode', $this->trip_mode]);
        $query->groupBy(['t.vehicle_trip_code', 't.vehicle_code', 't.trip_code', 't.transaction_date', 't.grn_no', 't.trip_status',
            't.trip_mode', 't.union_code', 't.plant_code', 't.mcc_plant_code', 't.bmc_code']);
        $query->orderBy(['transaction_date' => SORT_DESC, 'vehicle_trip_code' => SORT_ASC]);
        return $dataProvider;
    }

}
