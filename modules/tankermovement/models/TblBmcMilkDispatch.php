<?php

namespace app\modules\tankermovement\models;

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

    public $transporter_code;

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
        return [
                [['from_date', 'to_date', 'from_shift_code', 'to_shift_code', 'destination_type', 'destination_code', 'vehicle_code', 'trip_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'vehicle_in_time', 'vehicle_out_time', 'gross_weight', 'tare_weight', 'transaction_date'], 'required', 'except' => ['androidsync', 'importCsv']],
                [['bmc_milk_dispatch_code', 'challan_no', 'destination_type', 'destination_code', 'vehicle_code', 'trip_code', 'driver_name', 'driver_contact_no', 'authorizer_name', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['transaction_date', 'from_date', 'to_date', 'vehicle_in_time', 'vehicle_out_time', 'created_at', 'updated_at'], 'safe'],
                [['from_shift_code', 'to_shift_code', 'is_last_destination', 'purchase_rate_code', 'originating_type'], 'safe'],
                [['gross_weight', 'tare_weight'], 'number'],
                [['from_date', 'to_date', 'from_shift_code', 'to_shift_code'], 'CheckDateValidation', 'skipOnError' => true, 'on' => 'create'],
            //  [['transaction_date'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['bmc_code'], 'ValidateData', 'skipOnError' => true, 'on' => 'create'],
                [['union_code'], 'required', 'except' => ['androidsync', 'importCsv']],
                [['bmc_code'], 'ValidateTripCode', 'skipOnError' => true, 'on' => 'importCsv'],
        ];
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
            'is_last_destination' => Yii::t('app', 'Last Dest. ?'),
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
            $stock_date = TblBmcDispatchStock::find()->where(['bmc_code' => $this->bmc_code])->orderBy(['to_date' => SORT_DESC])->one();
            if (!empty($stock_date)) {
                $dispatch_date = ($stock_date->type == 'dispatch') ? date('Y-m-d H:i:s', strtotime('+12 hours', strtotime($stock_date->to_date))) : $stock_date->to_date;
                $dispatch_date .= '.000000';
                if ($this->from_date < $dispatch_date) {
                    $this->addError('to_date', Yii::t('app/validation', 'Dispatch already done for selected date.'));
                    return FALSE;
                } else if ($this->from_date > $dispatch_date) {
                    $this->addError('to_date', Yii::t('app/validation', 'From Date must be last stock date.'));
                    return FALSE;
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

}
