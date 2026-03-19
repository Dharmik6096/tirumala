<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlantConversionVendorMapping;
use app\modules\tankermovement\models\TblBmcMilkDispatch;

/**
 * This is the model class for table "tbl_vehicle_trip".
 *
 * @property string $vehicle_trip_code
 * @property string $vehicle_code
 * @property string $trip_code
 * @property string $grn_no
 * @property string $transaction_date
 * @property string $trip_status
 * @property string $trip_for
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblVehicleTrip extends \app\models\ChildModel {

    public $transporter_code, $is_last_destination, $challan_no, $bmc_detail, $total_qty, $rejected_count, $kg_fat, $kg_snf, $filter_plant_code;
    public $fl_type, $fl_code, $type;
    public $generateAutoTrip = FALSE, $is_not_actual_plant;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_trip';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vehicle_code', 'transaction_date', 'union_code', 'plant_code'], 'required', 'except' => ['closetrip', 'autogeneratetrip', 'chekinout']],
            [['vehicle_trip_code', 'vehicle_code', 'trip_code', 'grn_no', 'trip_status', 'trip_for', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'no_of_compartment', 'vehicle_capacity', 'remark', 'force_close', 'force_close_remarks'], 'safe'],
            [['transaction_date', 'created_at', 'updated_at', 'originating_type', 'transporter_code', 'is_last_destination', 'trip_mode', 'is_active', 'is_auto_trip', 'trip_sub_status', 'sub_status_time', 'driver_name', 'mobile_no', 'generateAutoTrip', 'is_check_in', 'check_in_type', 'check_in_code', 'check_in_datetime', 'is_not_actual_plant'], 'safe'],
            [['trip_status'], 'default', 'value' => 'generated'],
            [['trip_for'], 'default', 'value' => 'bmcdispatch'],
            [['trip_mode'], 'default', 'value' => 'online'],
            [['is_active'], 'default', 'value' => 1],
            [['is_auto_trip'], 'default', 'value' => 0],
            [['is_check_in'], 'default', 'value' => 0],
            [['vehicle_code'], 'checkVehicleStatus', 'on' => ['createTrip', 'autogeneratetrip']],
            [['force_close_remarks'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vehicle_trip_code' => Yii::t('app', 'Vehicle Trip Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'grn_no' => Yii::t('app', 'GRN No.'),
            'transaction_date' => Yii::t('app', 'Trip Date'),
            'trip_status' => Yii::t('app', 'Trip Status'),
            'trip_for' => Yii::t('app', 'Trip For'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'is_last_destination' => Yii::t('app', 'Is Last Destination ?'),
            'trip_mode' => Yii::t('app', 'Mode'),
            'challan_no' => Yii::t('app', 'Challan No.'),
            'bmc_detail' => Yii::t('app', 'Org Detail'),
            'kg_fat' => Yii::t('app', 'FATKg'),
            'kg_snf' => Yii::t('app', 'SNFKg'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'rejected_count' => Yii::t('app', 'Rejected Sample'),
            'trip_sub_status' => Yii::t('app', 'Trip Sub Status'),
            'sub_status_time' => Yii::t('app', 'Sub Status Time'),
            'driver_name' => Yii::t('app', 'Driver Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
        ];
    }

    public function getVehicleCode() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getBmcMilkDispatchCode() {
        return $this->hasMany(TblBmcMilkDispatch::className(), ['trip_code' => 'trip_code']);
    }

    public function setModel() {
        $save_model = [];
        $validate = TRUE;
        $inspection = FALSE;
        $api_res = FALSE;
        $vehicle_trip_detail_code = '';
        $this->transaction_date = date('Y-m-d', strtotime($this->transaction_date));
        $this->originating_org_code = $this->union_code;
        $model = $this->find()->where(['vehicle_code' => $this->vehicle_code])
                ->andWhere(['!=', 'trip_status', 'closed'])
                ->andWhere(['is_active' => 1])
                ->andWhere(['transaction_date' => $this->transaction_date])
                ->orderBy(['transaction_date' => SORT_ASC, 'created_at' => SORT_ASC])
                ->one();
        if (empty($model) && $this->trip_mode == 'online') {
            $model = $this->find()->where(['vehicle_code' => $this->vehicle_code])
                    ->andWhere(['!=', 'trip_status', 'closed'])
                    ->andWhere(['is_active' => 1])
                    ->andWhere(['<=', 'CAST(transaction_date as date)', $this->transaction_date])
                    ->orderBy(['transaction_date' => SORT_DESC, 'created_at' => SORT_DESC])
                    ->one();
            if (!empty($model)){
                $this->transaction_date = $model->transaction_date;
            }
        }
        $same_day_trip_count = $this->find()->where(['vehicle_code' => $this->vehicle_code])
                        ->andWhere(['!=', 'trip_status', 'closed'])
                        ->andWhere(['is_active' => 1])
                        ->andWhere(['transaction_date' => $this->transaction_date])->count();

        if (!empty($model) && (($this->trip_mode != 'offline') || ($same_day_trip_count > 1))) {
            $api_res = TRUE;
            if ($model->trip_status == 'generated') {
                $inspection = TRUE;
            }
            $last_trip_date = $model->transaction_date;
            if ($last_trip_date != $this->transaction_date) {
                $validate = FALSE;
                $this->addError('vehicle_code', Yii::t('app', 'First need to close Trip No. ' . $model->trip_code));
            } else if ($model->trip_status == 'tankerfull') {
                $validate = FALSE;
                $this->addError('vehicle_code', Yii::t('app', 'Tanker is Full Trip No. ' . $model->trip_code));
            } else if ($this->trip_mode == 'offline') {
                $validate = FALSE;
                $this->addError('vehicle_code', Yii::t('app', '2 Trips are already open for Tanker.'));
            }
            if ($this->is_last_destination == 1) {
                $model->trip_status = 'tankerfull';
                $save_model[] = $model;
            }
        } else {
            $inspection = TRUE;
            $this->vehicle_trip_code = Yii::$app->general->getPrimaryCode($this);
            $this->trip_code = $this->generateTripCode();
            $vehicle_trip_detail_code = $this->vehicle_trip_code . 'T1';
            $model = $this;
            $this->trip_sub_status = 'generated';
            $this->sub_status_time = date('Y-m-d H:i:s');
            if ($this->generateAutoTrip) {
                $this->is_auto_trip = 1;
                $driverDetail = $this->vehicleCode;
                $this->driver_name = $driverDetail->driver_name;
                $this->mobile_no = $driverDetail->driver_contact_no;
            }
            $save_model[] = $this;
        }
        if ($validate) {
            $trip_detai = new TblVehicleTripDetail();
            $last_trip = TblVehicleTripDetail::find()
                    ->where(['vehicle_code' => $this->vehicle_code, 'trip_code' => $model->trip_code])
                    //->andWhere(['<=', 'CAST(transaction_datetime as date)', $this->transaction_date])
                    ->orderBy(['sequence_no' => SORT_DESC])
                    ->one();
            if (!empty($last_trip)) {
                $trip_detai->source_org_code = $last_trip->destination_code;
                $trip_detai->source_org_type = $last_trip->destination_type;
                $trip_detai->sequence_no = $last_trip->sequence_no + 1;
            } else {
                $trip_detai->source_org_code = $model->plant_code;
                $trip_detai->source_org_type = 'plant';
                $trip_detai->sequence_no = 1;
            }
            $trip_detai->originating_org_code = $this->union_code;
            $trip_detai->destination_code = !empty($this->fl_code) ? $this->fl_code : $this->bmc_code;
            $trip_detai->destination_type = !empty($this->fl_type) ? $this->fl_type : 'bmc';
            $trip_detai->vehicle_trip_code = $model->vehicle_trip_code;
            $trip_detai->vehicle_code = $model->vehicle_code;
            $trip_detai->transaction_datetime = date('Y-m-d H:i:s');
            $trip_detai->trip_code = $model->trip_code;
            if ($this->generateAutoTrip) {
                $trip_detai->departure_time = date('Y-m-d H:i:s');
                $trip_detai->arrival_time = date('Y-m-d H:i:s', strtotime($trip_detai->departure_time) - 1);
            }
            if (empty($vehicle_trip_detail_code)) {
                $vehicle_trip_detail_code = $trip_detai->vehicle_trip_code . 'T' . (((int) substr($last_trip->vehicle_trip_detail_code, strlen($trip_detai->vehicle_trip_code) + 1)) + 1);
            }
            $trip_detai->vehicle_trip_detail_code = $vehicle_trip_detail_code;
            if (!$this->generateAutoTrip) {
                if (!$trip_detai->validate()) {
                    $validate = FALSE;
                    $errors = $trip_detai->getErrors();
                    if (isset($errors['destination_code'])) {
                        $this->addError('bmc_code', $errors['destination_code'][0]);
                    }
                }
            }
            $save_model[] = $trip_detai;
            if ($this->generateAutoTrip) {
                $numeric_part = intval(substr($trip_detai->vehicle_trip_detail_code, -1));
                $updated_numeric_part = $numeric_part + 1;
                $new_vehicle_trip_detail_code = $trip_detai->vehicle_trip_code . 'T' . $updated_numeric_part;
                $auto_trip_detail = new TblVehicleTripDetail();
                $auto_trip_detail->scenario = 'autoTrip';
                $auto_trip_detail->vehicle_trip_detail_code = $new_vehicle_trip_detail_code;
                $auto_trip_detail->vehicle_trip_code = $trip_detai->vehicle_trip_code;
                $auto_trip_detail->vehicle_code = $trip_detai->vehicle_code;
                $auto_trip_detail->trip_code = $trip_detai->trip_code;
                $auto_trip_detail->transaction_datetime = date('Y-m-d H:i:s');
                $auto_trip_detail->destination_code = NULL;
                $auto_trip_detail->destination_type = NULL;
                $auto_trip_detail->is_last_destination = (int) $model->is_last_destination;
                $auto_trip_detail->source_org_code = $trip_detai->destination_code;
                $auto_trip_detail->source_org_type = $trip_detai->destination_type;
                $auto_trip_detail->arrival_time = date('Y-m-d H:i:s', strtotime($trip_detai->departure_time) + 1);
                $auto_trip_detail->sequence_no = $trip_detai->sequence_no + 1;
                $auto_trip_detail->originating_org_code = $this->union_code;
                $save_model[] = $auto_trip_detail;
            }
        }
        $api_response = [];
        //if ($model->trip_mode == 'online') {
        $api_response['inspection_require'] = $inspection;
        $api_response['trip_code'] = $model->trip_code;
        $api_response['trip_status'] = $model->trip_status;
        $api_response['vehicle_trip_detail_code'] = $vehicle_trip_detail_code;
        //}
        return [$validate, $save_model, $api_response, $api_res];
    }

    public function generateTripCode() {
        $bmc_plant_code = !empty($this->fl_code) ? $this->fl_code : $this->bmc_code;
        $primaryKey = 'trip_code';
        $prefix = substr($this->vehicleCode->parsing_no, -4) . substr($bmc_plant_code, -2);
        $prefix = str_replace("-", "0", $prefix);
        $len = strlen($prefix);
        $val = $this->find()
                ->select(["MAX(CONVERT(INT,substring(" . $primaryKey . ", " . $len . " +1,4))) AS " . $primaryKey])
                ->where("SUBSTRING(" . $primaryKey . ", 1," . $len . ")='" . trim($prefix) . "'")
                ->one();
        $code1 = (int) $val[$primaryKey] + 1;
        return $prefix . $code1;
    }

    public function dispatchConsolidatedSummary() {
        return $this->find()->alias('t')->select(['t.vehicle_trip_code', 't.vehicle_code', 't.trip_code', 't.transaction_date', 't.grn_no', 't.trip_status',
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
                        ])
                        ->where(['t.vehicle_trip_code' => $this->vehicle_trip_code])
                        ->joinWith(['vehicleCode', 'vehicleCode.transporter', 'bmcMilkDispatchCode', 'bmcMilkDispatchCode.bmcMilkDispatchTxnCode'])
                        ->groupBy(['t.vehicle_trip_code', 't.vehicle_code', 't.trip_code', 't.transaction_date', 't.grn_no', 't.trip_status',
                            't.trip_mode', 't.union_code', 't.plant_code', 't.mcc_plant_code', 't.bmc_code'])
                        ->one();
    }

    public function getTripData() {
        return $this->find()
                        ->where(['trip_code' => $this->trip_code, 'lower(trip_status)' => ['tankerfull', 'open', 'generated']])->one();
    }

    public function getClosedtripData() {
        return $this->find()
                        ->where(['trip_code' => $this->trip_code, 'lower(trip_status)' => ['closed']])->one();
    }

    public function checkVehicleStatus($attribute, $params) {
        if ($this->hasErrors()) {
            return;
        }
        $existingTrip = $this->find()
                ->where(['vehicle_code' => $this->vehicle_code])
                ->andWhere(['NOT', ['trip_status' => 'closed']])
                ->one();
        if ($existingTrip) {
            $this->addError($attribute, 'A trip for this vehicle already exists and is not closed.');
        }
    }

    public function getVehicleTripDetailCode() {
        return $this->hasMany(TblVehicleTripDetail::className(), ['vehicle_trip_code' => 'vehicle_trip_code'])->onCondition(['and', ['arrival_time' => null], ['departure_time' => null]])->orderBy('sequence_no');
    }

    public function getTakenTripDetailCode() {
        return $this->hasMany(TblVehicleTripDetail::className(), ['vehicle_trip_code' => 'vehicle_trip_code'])->onCondition(['or', ['is not', 'arrival_time', null], ['is not', 'departure_time', null]])->orderBy('sequence_no');
    }

    public function addTripRoute(&$saveModel, &$deleteModel, $challan_no, $source_org_type, $source_org_code, $destination_type, $destination_code, &$validation, $is_last_destination = 0) {
        $trip_detail = TblVehicleTripDetail::find()
                        ->where(['trip_code' => $this->trip_code])
                        ->andWhere(['lower(source_org_type)' => $source_org_type, 'source_org_code' => $source_org_code])
                        ->andWhere(['IS', 'challan_no', NULL])
                        ->andWhere(['!=', 'vehicle_trip_detail_code', new \yii\db\Expression("CONCAT(vehicle_trip_code,'T1')")])
                        ->orderBy(['sequence_no' => SORT_ASC])->one();

        if (!empty($trip_detail)) {
            $trip_detail->challan_no = $challan_no;
            if ($this->is_auto_trip == 1) {
                if (empty($trip_detail->destination_code)) {
                    $trip_detail->destination_type = $destination_type;
                    $trip_detail->destination_code = $destination_code;
                    $isParty = strtolower($trip_detail->destination_type) == 'party';
                    $sequence = $trip_detail->sequence_no + 1;
                    $codeSuffix = intval(substr($trip_detail->vehicle_trip_detail_code, -1));
                    if ($isParty && $trip_detail->partyMasterCodeDest->party_type == 'conversion_vendor') {
                        $mappedModel = new TblPlantConversionVendorMapping();
                        $mappedPlant = $mappedModel->getMappedPlant($trip_detail->destination_code);
                        if (!empty($mappedPlant)) {
                            $virtul_trip_detail = new TblVehicleTripDetail();
                            $virtul_trip_detail->scenario = 'autoTrip';
                            $virtul_trip_detail->vehicle_trip_detail_code = $this->vehicle_trip_code . 'T' . ( ++$codeSuffix);
                            $virtul_trip_detail->vehicle_trip_code = $this->vehicle_trip_code;
                            $virtul_trip_detail->vehicle_code = $this->vehicle_code;
                            $virtul_trip_detail->trip_code = $this->trip_code;
                            $virtul_trip_detail->transaction_datetime = date('Y-m-d H:i:s');
                            $virtul_trip_detail->source_org_type = $trip_detail->destination_type;
                            $virtul_trip_detail->source_org_code = $trip_detail->destination_code;
                            $virtul_trip_detail->destination_type = 'plant';
                            $virtul_trip_detail->destination_code = $mappedPlant->plant_code;
                            $virtul_trip_detail->challan_no = $challan_no;
                            $virtul_trip_detail->is_virtual_location = 1;
                            $virtul_trip_detail->sequence_no = $sequence;
                            $saveModel[] = $virtul_trip_detail;

                            $next_virtul_trip_detail = new TblVehicleTripDetail();
                            $next_virtul_trip_detail->scenario = 'autoTrip';
                            $next_virtul_trip_detail->vehicle_trip_detail_code = $this->vehicle_trip_code . 'T' . ( ++$codeSuffix);
                            $next_virtul_trip_detail->vehicle_trip_code = $this->vehicle_trip_code;
                            $next_virtul_trip_detail->vehicle_code = $this->vehicle_code;
                            $next_virtul_trip_detail->trip_code = $this->trip_code;
                            $next_virtul_trip_detail->transaction_datetime = date('Y-m-d H:i:s');
                            $next_virtul_trip_detail->source_org_type = $virtul_trip_detail->destination_type;
                            $next_virtul_trip_detail->source_org_code = $virtul_trip_detail->destination_code;
                            $next_virtul_trip_detail->is_last_destination = (int) $is_last_destination;
                            $next_virtul_trip_detail->is_virtual_location = 2;
                            $next_virtul_trip_detail->sequence_no = $sequence + 1;
                            $saveModel[] = $next_virtul_trip_detail;
                        } else {
                            $validation = FALSE;
                        }
                    } else {
                        $auto_trip_detail = new TblVehicleTripDetail();
                        $auto_trip_detail->scenario = 'autoTrip';
                        $auto_trip_detail->vehicle_trip_detail_code = $this->vehicle_trip_code . 'T' . ( ++$codeSuffix);
                        $auto_trip_detail->vehicle_trip_code = $this->vehicle_trip_code;
                        $auto_trip_detail->vehicle_code = $this->vehicle_code;
                        $auto_trip_detail->trip_code = $this->trip_code;
                        $auto_trip_detail->transaction_datetime = date('Y-m-d H:i:s');
                        $auto_trip_detail->source_org_code = $destination_code;
                        $auto_trip_detail->source_org_type = $destination_type;
                        $auto_trip_detail->is_last_destination = (int) $is_last_destination;
                        $auto_trip_detail->sequence_no = $sequence;
                        $saveModel[] = $auto_trip_detail;
                    }
                } else if ($is_last_destination == 1) {
                    $exist_next_trip_detail = TblVehicleTripDetail::find()
                            ->where(['vehicle_trip_code' => $this->vehicle_trip_code, 'sequence_no' => $trip_detail->sequence_no + 1, 'source_org_code' => $trip_detail->destination_code])
                            ->andWhere(['lower(source_org_type)' => strtolower($trip_detail->destination_type)])
                            ->one();

                    if (!empty($exist_next_trip_detail) && $exist_next_trip_detail->is_virtual_location == 1) {
                        $exist_next_trip_detail = TblVehicleTripDetail::find()
                                ->where(['vehicle_trip_code' => $this->vehicle_trip_code, 'sequence_no' => $trip_detail->sequence_no + 2, 'source_org_code' => $exist_next_trip_detail->destination_code])
                                ->andWhere(['lower(source_org_type)' => strtolower($exist_next_trip_detail->destination_type)])
                                ->one();
                    }
                    $exist_next_trip_detail->scenario = 'autoTrip';

                    if (!empty($exist_next_trip_detail)) {
                        $historyModel = new TblVehicleTripDetailHistory();
                        Yii::$app->operation->history($exist_next_trip_detail, $historyModel, UPDATE);
                        $saveModel[] = $historyModel;

                        $exist_next_trip_detail->is_last_destination = 1;
                        $exist_next_trip_detail->destination_type = $exist_next_trip_detail->destination_code = NULL;
                        $saveModel[] = $exist_next_trip_detail;

                        $delete_next_all_trip_details = TblVehicleTripDetail::find()
                                ->where(['vehicle_trip_code' => $this->vehicle_trip_code])
                                ->andWhere(['>', 'sequence_no', $exist_next_trip_detail->sequence_no])
                                ->andWhere(['IS', 'challan_no', NULL])
                                ->all();

                        if (!empty($delete_next_all_trip_details)) {
                            foreach ($delete_next_all_trip_details as $next_trip_detail) {
                                $vehicleTripDetailHistoryModel = new TblVehicleTripDetailHistory();
                                Yii::$app->operation->history($next_trip_detail, $vehicleTripDetailHistoryModel, DELETE);
                                $saveModel[] = $vehicleTripDetailHistoryModel;
                                $deleteModel[] = $next_trip_detail;
                            }
                        }
                    }
                }
            }
            $saveModel[] = $trip_detail;
        }
    }

    public function setChildTable(&$model, &$modelSave, &$childModel) {
        $content = $modelSave['content'];
        $model->scenario = 'chekinout';
        $isValid = FALSE;
        if ($content['action_type'] == 'out') {
            $isValid = TRUE;
            $model->is_check_in = 0;
            $model->check_in_type = $model->check_in_code = $model->check_in_datetime = NULL;
        } else if ($content['action_type'] == 'in' && $model->is_check_in != '1') {
            $isValid = TRUE;
            $model->is_check_in = 1;
            $model->check_in_type = Yii::$app->eiplapp->identity->module_type;
            $model->check_in_code = Yii::$app->eiplapp->identity->module_code;
            $model->check_in_datetime = $content['check_in_datetime'];
        }
        if ($isValid) {
            $remarks = Yii::$app->eiplapp->identity->login_type . '-' . Yii::$app->eiplapp->identity->mobile_no . '-' . $content['action_type'];
            $sub_status_time = $model->sub_status_time;
            $trip_sub_status = $model->trip_sub_status;
            $model->trip_sub_status = 'trip_check_' . $content['action_type'];
            $model->sub_status_time = $content['check_in_datetime'];
            $trackingDetail = ['visibility_status' => 1, 'module_code' => NULL, 'module_type' => NULL];
            Yii::$app->general->setVehicleTripTrackingDetail($model, $trackingDetail, $remarks);
            $model->trip_sub_status = $trip_sub_status;
            $model->sub_status_time = $sub_status_time;
            $model->updated_by = Yii::$app->eiplapp->identity->module_code;
        }
    }

    public function addAutoQaCleaning($remarks) {
        $isVirtualLocation = TblVehicleTripDetail::find()->alias('td')
                        ->select(['td.is_virtual_location'])
                        ->leftJoin('tbl_vehicle_trip t', 't.trip_code = td.trip_code')
                        ->where(['td.trip_code' => $this->trip_code, 'td.source_org_type' => 'plant', 'td.source_org_code' => $this->plant_code, 'td.is_last_destination' => 1])
                        ->orderBy(['td.sequence_no' => SORT_ASC])
                        ->asArray()
                        ->one()['is_virtual_location'];

        if ($isVirtualLocation == '2') {
            $vehicleCleaningInspection = new TblVehicleCleaningInspection();
            $vehicleCleaningInspection->union_code = $this->union_code;
            $vehicleCleaningInspection->vehicle_code = $this->vehicle_code;
            $vehicleCleaningInspection->transporter_code = $this->vehicleCode->transporter_code;
            $vehicleCleaningInspection->trip_code = $this->trip_code;
            $vehicleCleaningInspection->transaction_datetime = date('Y-m-d H:i:s');
            if ($vehicleCleaningInspection->save()) {
                $trackingDetail = ['visibility_status' => 1, 'module_code' => null, 'module_type' => null];
                $this->trip_sub_status = 'qa_pending';
                $this->sub_status_time = date('Y-m-d H:i:s', strtotime('+1 second', strtotime($this->sub_status_time)));
                Yii::$app->general->setVehicleTripTrackingDetail($this, $trackingDetail, $remarks);
            }

            $vehicleQaInspection = new TblVehicleQaInspection();
            $vehicleQaInspection->attributes = $vehicleCleaningInspection->attributes;
            $vehicleQaInspection->status = 'pending';
            if ($vehicleQaInspection->save()) {
                $this->trip_sub_status = 'tanker_qualified';
                $this->sub_status_time = date('Y-m-d H:i:s', strtotime('+1 second', strtotime($this->sub_status_time)));
                $trackingDetail = ['visibility_status' => 1, 'module_code' => null, 'module_type' => null];
                Yii::$app->general->setVehicleTripTrackingDetail($this, $trackingDetail, $remarks);
                $this->save();
            }
        }
    }
    public function getPlantCodeFromBmc($bmcCodeArray){
        $plantCodeArray = TblDcsBmc::find()->select('plant_code')
            ->where(['bmc_code' => $bmcCodeArray])
            ->asArray()
            ->distinct()
            ->all();
        return !empty($plantCodeArray) ? array_column($plantCodeArray, 'plant_code') : [];
    }

}
