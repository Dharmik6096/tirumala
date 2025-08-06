<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblMilkVehicleEntry;
use app\modules\general\models\TblProcessApproval;

/**
 * TblMilkVehicleEntrySearch represents the model behind the search form about `app\modules\tankermovement\models\TblMilkVehicleEntry`.
 */
class TblMilkVehicleEntrySearch extends TblMilkVehicleEntry {

    public $from_date, $to_date, $bmc_ref_code, $ref_code, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_date', 'to_date', 'milk_vehicle_entry_code', 'trip_code', 'grn_no', 'receipt_at', 'vehicle_entry_date', 'vehicle_code', 'arrival_time', 'tare_weight_time', 'qty', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_code', 'customer_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'customer_name', 'bmc_ref_code', 'ref_code', 'f_plant_code', 'approved_at', 'approved_by', 'approval_status', 'approval_remarks', 'dock_no', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code'], 'safe'],
            [['gross_weight', 'tare_weight'], 'number'],
            [['originating_type'], 'integer'],
            [['from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => 'changeTrip'],
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
    public function search($params, $pending_approval = false) {
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
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'customerType', 'bmcCode']);
        // grid filtering conditions

        if ($pending_approval) {
            $approval = new TblProcessApproval();
            $subQuery = $approval->getApproveLavel('tbl_milk_vehicle_entry');
            $query->innerJoin(['ap' => $subQuery], 'convert(varchar(max),tbl_milk_vehicle_entry.milk_vehicle_entry_code) = convert(varchar(max),ap.process_code)');
            $query->addSelect(['tbl_milk_vehicle_entry.*', 'ap.process_approval_code as process_approval_code']);
            $this->approval_status = ['Pending', 'Inprogress'];
            $query->where(['tbl_milk_vehicle_entry.approval_status' => $this->approval_status]);
        }
        if (!empty($this->f_union_code)) {
            $query->andFilterWhere(['tbl_milk_vehicle_entry.union_code' => $this->f_union_code]);
        }
        if (!empty($this->f_plant_code)) {
            $query->andFilterWhere(['tbl_milk_vehicle_entry.plant_code' => $this->f_plant_code]);
        }
        if (!empty($this->f_mcc_code)) {
            $query->andFilterWhere(['tbl_milk_vehicle_entry.mcc_plant_code' => $this->f_mcc_code]);
        }
        if (!empty($this->f_bmc_code)) {
            $query->andFilterWhere(['tbl_milk_vehicle_entry.bmc_code' => $this->f_bmc_code]);
        }
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
                ->andFilterWhere(['like', 'vehicle_entry_date', (!empty($this->vehicle_entry_date)) ? date('Y-m-d', strtotime($this->vehicle_entry_date)) : ''])
                ->andFilterWhere(['like', 'tbl_bmc.ref_code', $this->bmc_ref_code])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry.dock_no', $this->dock_no]);


        return $dataProvider;
    }

    public function approvalSearch($params) {
        $query = TblMilkVehicleEntryTransaction::find()->alias('t');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->innerJoin('tbl_milk_vehicle_entry m', 'm.milk_vehicle_entry_code = t.milk_vehicle_entry_code');

        $approval = new TblProcessApproval();
        $subQuery = $approval->getApproveLavel('tbl_milk_vehicle_entry');
        $query->innerJoin(['ap' => $subQuery], 'convert(varchar(max),m.milk_vehicle_entry_code) = convert(varchar(max),ap.process_code)');
        $query->addSelect(['t.*', 'ap.process_approval_code as process_approval_code']);
        $query->andFilterWhere(['m.approval_status' => ['Pending', 'Inprogress']]);

        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'CAST(m.vehicle_entry_date AS DATE)', $to_date]);
        }

        $query->orderBy([
            't.created_at' => SORT_DESC,
        ]);

        return $dataProvider;
    }

    public function searchEditTrip($params) {
        $this->load($params);
        if ($this->scenario == 'changeTrip') {
            if (empty($params) || !$this->validate()) {
                return [];
            }
        }
        $query = TblMilkVehicleEntry::find();
        $query->select(['tbl_milk_vehicle_entry.milk_vehicle_entry_code', 'plant_code' => "CONCAT(tbl_plant.name,'-',tbl_plant.plant_code,'-',tbl_plant.ref_code)", 'tbl_milk_vehicle_entry.grn_no', 'vehicle_entry_date' => 'convert(varchar,tbl_milk_vehicle_entry.vehicle_entry_date,105)', 'tbl_milk_vehicle_entry.qty', 'tbl_milk_vehicle_entry.gross_weight', 'tbl_milk_vehicle_entry.tare_weight', 'vehicle_code' => 'tbl_vehicle_master.parsing_no', 'current_trip_code' => 'tbl_milk_vehicle_entry.trip_code', 'tbl_milk_vehicle_entry.trip_code']);
        $query->joinWith(['vehicleCode', 'plantCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_vehicle_entry', 'tbl_milk_vehicle_entry', 'tbl_milk_vehicle_entry');

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'CAST(tbl_milk_vehicle_entry.vehicle_entry_date as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'CAST(tbl_milk_vehicle_entry.vehicle_entry_date as date)', $to_date]);
        }

        $query->andFilterWhere([
            'tbl_milk_vehicle_entry.vehicle_code' => $this->vehicle_code,
            'tbl_milk_vehicle_entry.trip_code' => $this->trip_code
        ]);
        return $query->asArray()->all();
    }

}
