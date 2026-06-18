<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\organisation\models\TblDcsBmc;

/**
 * TblVehicleTripSearch represents the model behind the search form about `app\modules\tankermovement\models\TblVehicleTrip`.
 */
class TblVehicleTripSearch extends TblVehicleTrip {

    public $from_date, $to_date, $parsing_no;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vehicle_trip_code', 'vehicle_code', 'trip_code', 'grn_no', 'transaction_date', 'trip_status', 'trip_for', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'trip_mode', 'is_active', 'trip_sub_status', 'sub_status_time', 'driver_name', 'mobile_no', 'transporter_code', 'from_date', 'to_date', 'is_auto_trip', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'no_of_compartment', 'vehicle_capacity', 'remark', 'parsing_no', 'force_close', 'force_close_remarks', 'source_name', 'source_code', 'ref_code', 'source_type'], 'safe'],
            [['is_active'], 'integer'],
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
        $query = TblVehicleTrip::find()->alias('t')->select(['t.is_active', 't.vehicle_trip_code', 't.vehicle_code', 't.trip_code', 't.transaction_date', 't.grn_no', 't.trip_status', 't.driver_name', 't.mobile_no',
            't.trip_mode', 't.union_code', 't.plant_code', 't.mcc_plant_code', 't.bmc_code', 't.trip_sub_status', 't.trip_for', 't.is_auto_trip',
            'source_code' => 'td.destination_code',
            'source_type' => 'UPPER(td.destination_type)',
            'source_name' => "COALESCE(bmc.bmc_name, plant.name, party.party_name)",
            'ref_code' => "COALESCE(bmc.ref_code, plant.ref_code, party.sap_vendor_code)",
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
            't.remark', 't.vehicle_capacity', 't.no_of_compartment', 't.force_close', 't.force_close_remarks'
        ]);

        // add conditions that should always apply here
        $query->innerJoin('tbl_vehicle_trip_detail td', 'td.vehicle_trip_code = t.vehicle_trip_code AND td.sequence_no = 1');
        $query->leftJoin('tbl_bmc bmc', "td.destination_type = 'bmc' AND bmc.bmc_code = td.destination_code");
        $query->leftJoin('tbl_plant plant', "td.destination_type = 'plant' AND plant.plant_code = td.destination_code");
        $query->leftJoin('tbl_party_master party', "td.destination_type = 'party' AND party.party_master_code = td.destination_code");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['vehicleCode', 'vehicleCode.transporter', 'bmcMilkDispatchCode', 'bmcMilkDispatchCode.bmcMilkDispatchTxnCode']);

        $plants = !empty($this->f_plant_code) ? $this->f_plant_code : (!empty(Yii::$app->session->get('Plant')) ? explode(',', Yii::$app->session->get('Plant')) : '');
        $mccs = !empty($this->f_mcc_code) ? $this->f_mcc_code : (!empty(Yii::$app->session->get('MCC')) ? explode(',', Yii::$app->session->get('MCC')) : '');
        $bmcs = !empty($this->f_bmc_code) ? $this->f_bmc_code : (!empty(Yii::$app->session->get('BMC')) ? explode(',', Yii::$app->session->get('BMC')) : '');

        if (!empty($bmcs) || !empty($mccs) || !empty($plants)) {
            $conditions = ['or'];
            if (!empty($bmcs)) {
                $conditions[] = ['and', ['in', 'source_org_code', $bmcs], ['source_org_type' => 'bmc']];
            } else if (!empty($mccs)) {
                $bmcData = TblDcsBmc::find()->select('bmc_code')->where(['mcc_plant_code' => $mccs])->column();
                $conditions[] = ['and', ['in', 'source_org_code', $bmcData], ['source_org_type' => 'bmc']];
            } else if (!empty($plants)) {
                $conditions[] = ['and', ['in', 'source_org_code', $plants], ['source_org_type' => 'plant']];
            }

            $subQuery = TblVehicleTripDetail::find()
                    ->select(new \yii\db\Expression(1))
                    ->where('tbl_vehicle_trip_detail.vehicle_trip_code = t.vehicle_trip_code')
                    ->andWhere($conditions);

            $query->andWhere(['exists', $subQuery]);
        }
        if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['t.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        }
        $query->andFilterWhere(['t.union_code' => $this->f_union_code]);

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 't.transaction_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 't.transaction_date', $to_date]);
        }
        if(empty($this->trip_status)){
            $query->andWhere(['not', ['t.trip_status' => 'closed']]);
        }

        $query->andFilterWhere(['=', 't.transaction_date', !empty($this->transaction_date) ? date('Y-m-d', strtotime($this->transaction_date)) : NULL]);
        $query->andFilterWhere([
            't.is_active' => $this->is_active,
            'tbl_transporter.transporter_code' => $this->transporter_code,
            't.vehicle_code' => $this->vehicle_code,
            't.is_auto_trip' => $this->is_auto_trip,
            't.trip_status' => $this->trip_status,
            't.force_close' => $this->force_close,
        ]);
        $query->andFilterWhere(['like', 't.trip_code', $this->trip_code])
                ->andFilterWhere(['like', 't.grn_no', $this->grn_no])
                ->andFilterWhere(['like', 't.trip_sub_status', $this->trip_sub_status])
                ->andFilterWhere(['like', 't.trip_mode', $this->trip_mode])
                ->andFilterWhere(['like', 't.remark', $this->remark])
                ->andFilterWhere(['like', 'tbl_vehicle_master.parsing_no', $this->parsing_no])
                ->andFilterWhere(['like', 't.driver_name', $this->driver_name])
                ->andFilterWhere(['like', 't.force_close_remarks', $this->force_close_remarks]);
        if (!empty($this->ref_code)) {
            $query->andWhere(['or', ['like', 'bmc.ref_code', $this->ref_code], ['like', 'plant.ref_code', $this->ref_code], ['like', 'party.sap_vendor_code', $this->ref_code]]);
        }
        $query->groupBy(['t.is_active', 't.vehicle_trip_code', 't.vehicle_code', 't.trip_code', 't.transaction_date', 't.grn_no', 't.trip_status',
            't.trip_mode', 't.union_code', 't.plant_code', 't.mcc_plant_code', 't.bmc_code', 't.trip_sub_status', 't.trip_for', 't.is_auto_trip', 't.driver_name', 't.mobile_no', 't.remark', 't.vehicle_capacity', 't.no_of_compartment', 't.force_close', 't.force_close_remarks', 'td.destination_code', 'td.destination_type', 'bmc.bmc_name', 'plant.name', 'party.party_name', 'bmc.ref_code', 'plant.ref_code', 'party.sap_vendor_code']);
        $query->orderBy(['transaction_date' => SORT_DESC, 'vehicle_trip_code' => SORT_ASC]);
        return $dataProvider;
    }

}
