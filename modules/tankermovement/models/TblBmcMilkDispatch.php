<?php

namespace app\modules\tankermovement\models;

use app\modules\collection\models\TblMccShiftLock;
use Yii;
use app\modules\tankermovement\models\TblBmcDispatchStock;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\dcsoperation\models\TblShift;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxn;
use app\modules\organisation\models\TblBmcSilosInfo;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_bmc_milk_dispatch".
 *
 * @property string $bmc_milk_dispatch_code
 * @property string $challan_no
 * @property string $transaction_date
 * @property string $from_date
 * @property integer $from_shift_code
 * @property string $to_date
 * @property integer $to_shift_code
 * @property string $destination_type
 * @property string $destination_code
 * @property string $vehicle_code
 * @property string $trip_code
 * @property string $driver_name
 * @property string $driver_contact_no
 * @property string $authorizer_name
 * @property string $vehicle_in_time
 * @property string $vehicle_out_time
 * @property string $remarks
 * @property string $gross_weight
 * @property string $tare_weight
 * @property integer $is_last_destination
 * @property integer $purchase_rate_code
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
class TblBmcMilkDispatch extends \app\models\ChildModel {

    public $transporter_code, $is_clr_input;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_milk_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['from_date', 'to_date', 'from_shift_code', 'to_shift_code', 'destination_type', 'destination_code', 'vehicle_code', 'trip_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'vehicle_in_time', 'transaction_date'], 'required', 'except' => ['androidsync', 'importCsv', 'createPlantDispatch', 'tripUpdate']],
                [['vehicle_out_time'], 'required', 'except' => ['androidsync', 'importCsv', 'createPlantDispatch', 'create', 'tripUpdate']],
                [['bmc_milk_dispatch_code', 'challan_no', 'destination_type', 'destination_code', 'vehicle_code', 'trip_code', 'driver_name', 'driver_contact_no', 'authorizer_name', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'source_org_code', 'source_org_type', 'is_clr_input', 'tested_by'], 'safe'],
                [['transaction_date', 'from_date', 'to_date', 'vehicle_in_time', 'vehicle_out_time', 'created_at', 'updated_at'], 'safe'],
                [['from_shift_code', 'to_shift_code', 'is_last_destination', 'purchase_rate_code', 'originating_type'], 'safe'],
                [['from_date', 'to_date', 'from_shift_code', 'to_shift_code'], 'CheckDateValidation', 'skipOnError' => true, 'on' => ['create', 'createPlantDispatch']],
                [['bmc_code'], 'ValidateData', 'skipOnError' => true, 'on' => 'create'],
                [['union_code'], 'required', 'except' => ['androidsync', 'importCsv']],
                [['bmc_code'], 'ValidateTripCode', 'skipOnError' => true, 'on' => 'importCsv'],
                [['from_date', 'to_date', 'from_shift_code', 'to_shift_code', 'destination_type', 'destination_code', 'vehicle_code', 'trip_code', 'union_code', 'plant_code', 'vehicle_in_time', 'transaction_date'], 'required', 'on' => 'createPlantDispatch'],
                [['originating_org_code', 'originating_org_type'], function($attribute, $params) {
                    $this->source_org_code = $this->originating_org_code;
                    $this->source_org_type = $this->originating_org_type;
                }, 'on' => 'androidsync'],
                [['tested_by'], 'string', 'max' => 100],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblBmcMilkDispatch', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_milk_dispatch_code' => Yii::t('app', 'Bmc Milk Dispatch Code'),
            'challan_no' => Yii::t('app', 'Challan No.'),
            'transaction_date' => Yii::t('app', 'Dispatch Date'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift_code' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift_code' => Yii::t('app', 'To Shift'),
            'destination_type' => Yii::t('app', 'Dest. Type'),
            'destination_code' => Yii::t('app', 'Dest. Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'driver_name' => Yii::t('app', 'Driver Name'),
            'driver_contact_no' => Yii::t('app', 'Driver Contact No.'),
            'authorizer_name' => Yii::t('app', 'Authorizer Name'),
            'vehicle_in_time' => Yii::t('app', 'In Time'),
            'vehicle_out_time' => Yii::t('app', 'Out Time'),
            'remarks' => Yii::t('app', 'Remarks'),
            'gross_weight' => Yii::t('app', 'Gross Wt.'),
            'tare_weight' => Yii::t('app', 'Tare Wt.'),
            'is_last_destination' => Yii::t('app', 'Is Last Destination'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
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
            'f_plant_code' => Yii::t('app', 'Plant'),
            'f_mcc_code' => Yii::t('app', 'MCC'),
            'f_bmc_code' => Yii::t('app', 'BMC'),
            'source_org_code' => Yii::t('app', 'Source Org Code'),
            'source_org_type' => Yii::t('app', 'Source Org Type'),
            'tested_by' => Yii::t('app', 'Tested By'),
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

    public function getCustomerCodeSource() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'source_org_code']);
    }

    public function getBmcCodeSource() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'source_org_code']);
    }

    public function getMccPlantCodeSource() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'source_org_code']);
    }

    public function getPlantCodeSource() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'source_org_code']);
    }

    public function getCustomerCodeDest() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'destination_code']);
    }

    public function getBmcCodeDest() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'destination_code']);
    }

    public function getMccPlantCodeDest() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'destination_code']);
    }

    public function getPlantCodeDest() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'destination_code']);
    }

    public function getPartyMasterCodeDest() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'destination_code']);
    }

    public function getPartyMasterCodeSource() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'source_org_code']);
    }

    public function getFromShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift_code']);
    }

    public function getToShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift_code']);
    }

    public function getBmcMilkDispatchTxnCode() {
        return $this->hasMany(TblBmcMilkDispatchTxn::className(), ['bmc_milk_dispatch_code' => 'bmc_milk_dispatch_code']);
    }

    public function ValidateData() {
        if (strtolower($this->destination_type) == 'bmc' && $this->bmc_code == $this->destination_code) {
            $this->addError('destination_code', Yii::t('app/validation', 'Destination can not be source BMC.'));
        } else if (date('Y-m-d', strtotime($this->transaction_date)) < date('Y-m-d', strtotime($this->to_date))) {
            $this->addError('transaction_date', Yii::t('app/validation', 'Dispatch Date can not be less than To Date.'));
        }
    }

    public function CheckDateValidation() {
        $this->from_date = date('Y-m-d', strtotime($this->from_date)) . ' ' . \Yii::$app->general->getshift($this->from_shift_code) . '.000000';
        $this->to_date = date('Y-m-d', strtotime($this->to_date)) . ' ' . \Yii::$app->general->getshift($this->to_shift_code) . '.000000';
        if ($this->to_date < $this->from_date) {
            $this->addError('to_date', Yii::t('app/validation', 'To Date must not be less than from date.'));
            return FALSE;
        } else {
            if ($this->getScenario() == 'create') {
                $stock_date = TblBmcDispatchStock::find()->where(['bmc_code' => $this->bmc_code])
                        ->orderBy(['to_date' => SORT_DESC, 'created_at' => SORT_DESC])
                        ->one();
                if (!empty($stock_date)) {
//                    $dispatch_date = ($stock_date->type == 'dispatch') ? date('Y-m-d H:i:s', strtotime('+12 hours', strtotime($stock_date->to_date))) . '.000000' : $stock_date->to_date;
                    $dispatch_date = date('Y-m-d H:i:s', strtotime('+12 hours', strtotime($stock_date->to_date))) . '.000000';

                    $formatted_shift = date('H', strtotime($dispatch_date));
                    if ($formatted_shift) {
                        $formatted_shift = $formatted_shift == 18 ? 'Evening' : 'Morning';
                    }
                    $formatted_date = date('d-m-Y', strtotime($dispatch_date));

                    $dispatch_count = TblBmcMilkDispatch::find()
                            ->where(['to_date' => $this->to_date, 'trip_code' => $this->trip_code, 'vehicle_code' => $this->vehicle_code])
                            ->andWhere(['bmc_code' => $this->bmc_code])
                            ->count();
                    if ($dispatch_count > 0) {
                        $this->addError('to_date', Yii::t('app/validation', 'Dispatch already done for selected date. Please select this date: ' . $formatted_date . ' and shift ' . $formatted_shift));
                        return FALSE;
                    } else {
                        if ($stock_date->from_date == $this->from_date && $stock_date->to_date == $this->to_date) {
                            return TRUE;
                        } else if ($stock_date->to_date == $this->from_date && $stock_date->to_date == $this->to_date) {
                            return TRUE;
                        } else if ($this->from_date < $stock_date->to_date) {
                            $this->addError('to_date', Yii::t('app/validation', 'Dispatch already done for selected date. Please select this date: ' . $formatted_date . ' and shift ' . $formatted_shift));
                            return FALSE;
                        } else if ($this->from_date > $dispatch_date) {
                            $this->addError('to_date', Yii::t('app/validation', 'From Date must be last stock date. Please select this date: ' . $formatted_date . ' and shift ' . $formatted_shift));
                            return FALSE;
                        }
                    }
                }
            }
        }
        return TRUE;
    }

    public function getTripDetail() {
        $status = 'success';
        $error_msg = '';
        $trip_code = '';
        if ($this->CheckDateValidation()) {
            /*  $trip = TblVehicleTrip::find()->where(['vehicle_code' => $this->vehicle_code, 'lower(trip_status)' => ['generated', 'open']])->one();
              if (!empty($trip)) {
              $trip_code = $trip->trip_code;
              } else {
              $status = 'error';
              $error_msg = Yii::t('app/validation', 'Trip No. not available.');
              } */
        } else {
            $status = 'error';
            $error_msg = $this->getErrors()['to_date'][0];
        }
        return ['status' => $status, 'msg' => $error_msg, 'trip_code' => $trip_code];
    }

    public function getBmcMilkDispatchTxn() {
        return $this->hasOne(TblBmcMilkDispatchTxn::className(), ['bmc_milk_dispatch_code' => 'bmc_milk_dispatch_code']);
    }

    public function ValidateTripCode() {
        $check_record = $this->find()->where(['challan_no' => $this->challan_no, 'transaction_date' => $this->transaction_date, 'bmc_code' => $this->bmc_code])->count();
        if ($check_record == 1) {
            $check_trip = TblVehicleTripDetail::find()
                            ->select(['tbl_vehicle_trip.trip_code', 'tbl_vehicle_trip.vehicle_code'])
                            ->distinct()
                            ->joinWith(['tripCode'])
                            ->where(['tbl_vehicle_trip.trip_code' => $this->trip_code, 'tbl_vehicle_trip.transaction_date' => $this->transaction_date])
                            ->andWhere(['tbl_vehicle_trip_detail.source_org_type' => 'bmc', 'tbl_vehicle_trip_detail.source_org_code' => $this->bmc_code])->all();
            if (count($check_trip) == 0) {
                $this->addError('to_date', Yii::t('app/validation', 'Invalid Trip Code.'));
            } else {
                $this->vehicle_code = $check_trip[0]->vehicle_code;
            }
        } else {
            $this->addError('to_date', Yii::t('app/validation', 'Invalid Combination of Bmc Code/Transaction Date/Challan No.'));
        }
    }

    public function getTripCode() {
        return $this->hasOne(TblVehicleTrip::className(), ['trip_code' => 'trip_code']);
    }

    public function getChallanNo() {
        $primaryKey = 'challan_no';
        $orgCode = ($this->bmc_code) ? $this->trip_code . '/' . $this->bmc_code . '/' : $this->trip_code . '/' . $this->plant_code . '/';
        $len = strlen($orgCode);
        $val = $this->find()
                ->select(["MAX(CONVERT(INT,substring(" . $primaryKey . ", " . $len . " +1,4))) AS " . $primaryKey])
                ->where("SUBSTRING(" . $primaryKey . ", 1," . $len . ")='" . trim($orgCode) . "'")
                ->one();
        $code = (int) $val[$primaryKey] + 1;
        $value = $orgCode . $code;
        return $value;
    }

    public function getTripDetailsCount() {
        $transaction_date = date('Y-m-d', strtotime($this->transaction_date));
        $count = TblVehicleTripDetail::find()
                ->joinWith(['tripCode'])
                ->Where([
                    'tbl_vehicle_trip.trip_status' => ['generated', 'open', 'tankerfull'],
                    'LOWER(tbl_vehicle_trip_detail.source_org_type)' => $this->source_org_type,
                    'tbl_vehicle_trip_detail.source_org_code' => $this->source_org_code,
                    'tbl_vehicle_trip_detail.trip_code' => $this->trip_code
                ])
                ->andWhere(['<=', 'transaction_date', $transaction_date])
                ->andWhere(['IS NOT', 'arrival_time', null])
                ->andWhere(['IS', 'departure_time', null])
                ->count();
        return $count == 1;
    }

    public function getFromDateToDate($is_physical_stock = false) {
        $bmcData = $this->bmcCode;
        $result = ['status' => 'error', 'from_datetime' => NULL, 'from_date' => null, 'from_shift' => null, 'to_datetime' => NULL, 'to_date' => null, 'to_shift' => null, 'physical_stock_only' => 0];
        $stock = TblBmcDispatchStock::find()
                ->where(['bmc_code' => $this->bmc_code])
                ->orderBy(['to_date' => SORT_DESC, 'created_at' => SORT_DESC])
                ->one();

        if (!empty($stock)) {
            $shiftLock = TblMccShiftLock::find()
                    ->where(['>=', 'date_time_of_collection', date('Y-m-d H:i:s', strtotime($stock->to_date))])
                    ->andWhere(['mcc_plant_code' => $bmcData->mcc_plant_code, 'bmc_lock' => 1])
                    ->orderBy(['date_time_of_collection' => SORT_DESC])
                    ->one();
            $oldShiftLock = TblMccShiftLock::find()
                    ->where(['>=', 'date_time_of_collection', date('Y-m-d H:i:s', strtotime($stock->to_date))])
                    ->andWhere(['mcc_plant_code' => $bmcData->mcc_plant_code, 'bmc_lock' => 1])
                    ->orderBy(['date_time_of_collection' => SORT_ASC])
                    ->one();

            // IF Not - check - Any previous Dispatch entry is available
            if (strtolower($stock->type) == 'physical') {
                if (empty($shiftLock) || $shiftLock->date_time_of_collection <= $stock->to_date) {
                    // Set - Both From Date & To Date as last Physical Stock Entry
                    $physical_stock_only = 1;
                    //  if (!empty($dispatch) && ($dispatch->to_date == $stock->to_date)) {
                    if (!empty($shiftLock) && ($shiftLock->created_at >= $stock->created_at)) {
                        $physical_stock_only = 0;
                    }

                    $result = [
                        'status' => 'success',
                        'from_datetime' => $stock->to_date,
                        'to_datetime' => $stock->to_date,
                        'from_date' => date('d-m-Y', strtotime($stock->to_date)),
                        'from_shift' => $stock->to_shift_code,
                        'to_date' => date('d-m-Y', strtotime($stock->to_date)),
                        'to_shift' => $stock->to_shift_code,
                        'physical_stock_only' => $physical_stock_only,
                    ];
                } else {
                    if (!empty($oldShiftLock) && ($oldShiftLock->created_at >= $stock->created_at)) {
                        $fromDate = $oldShiftLock->date_time_of_collection;
                        $fromShift = $oldShiftLock->shift_code;
                    } else {
                        $fromDate = date('Y-m-d H:i:s', strtotime('+12 hours', strtotime($stock->to_date)));
                        $fromShift = (date('H', strtotime($fromDate)) == 18) ? 2 : 1;
                    }
                    $result = [
                        // Set last physical stock entry date shift as from Date-shift. +12 Hr
                        'status' => 'success',
                        'from_datetime' => $fromDate,
                        'to_datetime' => $shiftLock->date_time_of_collection,
                        'from_date' => date('d-m-Y', strtotime($fromDate)),
                        'from_shift' => $fromShift,
                        // Latest RMRD Shift Lock Date
                        'to_date' => date('d-m-Y', strtotime($shiftLock->date_time_of_collection)),
                        'to_shift' => $shiftLock->shift_code,
                        'physical_stock_only' => 0
                    ];
                }
            } else {
                if (!empty($shiftLock) && $stock->to_date < $shiftLock->date_time_of_collection) {
                    if (!empty($oldShiftLock) && ($oldShiftLock->created_at >= $stock->created_at)) {
                        $fromDate = $oldShiftLock->date_time_of_collection;
                        $fromShift = $oldShiftLock->shift_code;
                    } else {
                        $fromDate = date('Y-m-d H:i:s', strtotime('+12 hours', strtotime($stock->to_date)));
                        $fromShift = (date('H', strtotime($fromDate)) == 18) ? 2 : 1;
                    }
                    $result = [
                        // Set previous dispatch to date shift +12 hours
                        'status' => 'success',
                        'from_datetime' => $fromDate,
                        'to_datetime' => $shiftLock->date_time_of_collection,
                        'from_date' => date('d-m-Y', strtotime($fromDate)),
                        'from_shift' => $fromShift,
                        // Set - Latest RMRD Shift Lock Date
                        'to_date' => date('d-m-Y', strtotime($shiftLock->date_time_of_collection)),
                        'to_shift' => $shiftLock->shift_code,
                        'physical_stock_only' => 0
                    ];
                } else {
                    $result = [
                        // Set previous dispatch from date & shift
                        'status' => 'success',
                        'from_datetime' => $stock->from_date,
                        'to_datetime' => $stock->to_date,
                        'from_date' => date('d-m-Y', strtotime($stock->from_date)),
                        'from_shift' => $stock->from_shift_code,
                        'to_date' => date('d-m-Y', strtotime($stock->to_date)),
                        'to_shift' => $stock->to_shift_code,
                        'physical_stock_only' => 1,
                    ];
                }
            }
        } else {
            if (!empty($is_physical_stock)) {
                $shift = 1;
                $date = date('Y-m-d') . ' ' . \Yii::$app->general->getshift($shift);
                $result = [
                    'status' => 'success',
                    'from_datetime' => $date,
                    'to_datetime' => $date,
                    'from_date' => date('d-m-Y'),
                    'from_shift' => $shift,
                    'to_date' => date('d-m-Y'),
                    'to_shift' => $shift,
                    'physical_stock_only' => 2,
                ];
            }
        }
        $stock_data = [];
        if (!empty($result['from_datetime']) && !empty($result['to_datetime'])) {
            $query = \Yii::$app->db->createCommand("{CALL sp_portal_bmc_purchase_detail (:bmc_code,:from_datetime,:to_datetime,:physical_stock_only)}")
                    ->bindValue(':from_datetime', date('Y-m-d H:i:s', strtotime($result['from_datetime'])))
                    ->bindValue(':to_datetime', date('Y-m-d H:i:s', strtotime($result['to_datetime'])))
                    ->bindValue(':bmc_code', $this->bmc_code)
                    ->bindValue(':physical_stock_only', $result['physical_stock_only']);
            $stock_data = $query->queryAll();
        }
        $result['stock_data'] = $stock_data;

        $stock_detail = [];
        if (!empty($stock_data)) {
            $dispatch_with_milk_type = 0;
            if (!$is_physical_stock) {
                $dispatchWithMilkTypeConfig = Yii::$app->general->getUnionConfiguration($bmcData->union_code, 'bmc_dispatch_with_milk_type', 'PORTAL');
                $dispatch_with_milk_type = ($dispatchWithMilkTypeConfig != '') ? $dispatchWithMilkTypeConfig : 0;
            }
            foreach ($stock_data as $r) {
                $animal_type = $is_physical_stock ? $r['animal_type_code'] : ($dispatch_with_milk_type == '1' ? $r['animal_type_code'] : '3');
                $key = $r['bmc_silos_info_code'] . '_' . $animal_type . '_' . $r['milk_quality_type_code'];
                if (empty($stock_detail[$key])) {
                    $stock_detail[$key]['previous_qty'] = 0;
                    $stock_detail[$key]['purchase_qty'] = 0;
                }
                $stock_detail[$key]['previous_qty'] = $stock_detail[$key]['previous_qty'] + $r['previous_qty'];
                $stock_detail[$key]['purchase_qty'] = $stock_detail[$key]['purchase_qty'] + $r['purchase_qty'];
            }
        }
        $result['stock_detail'] = $stock_detail;
        return $result;
    }

    public function getlastDestTripDetail() {
        $tripExists = TblVehicleTripDetail::find()
                ->joinWith(['tripCode'])
                ->where([
                    'tbl_vehicle_trip_detail.trip_code' => $this->trip_code,
                    'tbl_vehicle_trip_detail.arrival_time' => null,
                    'tbl_vehicle_trip.trip_status' => ['generated', 'open', 'tankerfull'],])
                ->orderBy(['tbl_vehicle_trip_detail.sequence_no' => SORT_DESC])
                ->one();

        if (!empty($tripExists)) {
            $stockDataCreatedAt = TblBmcDispatchStock::find()->select('created_at')->where(['from_date' => $this->from_date, 'to_date' => $this->to_date, 'bmc_code' => $this->bmc_code, 'type' => 'dispatch'])->orderBy(['created_at' => SORT_DESC])->scalar();
            $laterStockExists = true;
            if (!empty($stockDataCreatedAt)) {
                $laterStockExists = TblBmcDispatchStock::find()
                        ->where(['bmc_code' => $this->bmc_code,])
                        ->andWhere(['>', 'created_at', $stockDataCreatedAt])
                        ->exists();
            }
            return $laterStockExists ? false : true;
        } else {
            return false;
        }
    }

    public function getBmcSilosInfoList() {
        $data = TblBmcSilosInfo::find()->select(['bmc_silos_info_code', 'silo_no'])->where(['module_name' => 'BMC', 'module_code' => $this->bmc_code])->asArray()->all();
        return ArrayHelper::map($data, 'bmc_silos_info_code', 'silo_no');
    }

    public function getDispatchData($union, $tripCode, $chamberNo, $org_code) {
        $bmcMilkDispatchData = [];
        if (!empty($tripCode) && !empty($chamberNo)) {
            $bmcMilkDispatchData = $this->find()
                    ->select(['tbl_bmc_milk_dispatch.plant_code', 'tbl_bmc_milk_dispatch.bmc_code'])
                    ->joinWith(['bmcMilkDispatchTxnCode'])
                    ->where(['tbl_bmc_milk_dispatch.union_code' => $union, 'tbl_bmc_milk_dispatch.trip_code' => $tripCode, 'tbl_bmc_milk_dispatch_txn.chamber_no' => $chamberNo])
                    ->orderBy(['tbl_bmc_milk_dispatch.created_at' => SORT_DESC])
                    ->one();
        }
        if (!empty($bmcMilkDispatchData)) {
            if (!empty($bmcMilkDispatchData->bmc_code)) {
                return ['config' => 'BMC_DISPATCH_CONFIG', 'orgType' => 'BMC', 'orgCode' => $bmcMilkDispatchData->bmc_code];
            } else {
                return ['config' => 'PLANT_DISPATCH_CONFIG', 'orgType' => 'PLANT', 'orgCode' => $bmcMilkDispatchData->plant_code];
            }
        } else {
            return ['config' => 'PLANT_RECEIPT_CONFIG', 'orgType' => 'PLANT', 'orgCode' => $org_code];
        }
    }

}
