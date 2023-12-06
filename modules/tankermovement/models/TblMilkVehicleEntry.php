<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\tankermovement\models\TblSampleBottleTesting;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblDcs;
use app\modules\tankermovement\models\TblVehicleTripDetail;
use app\modules\tankermovement\models\TblPartyMaster;

/**
 * This is the model class for table "tbl_milk_vehicle_entry".
 *
 * @property string $milk_vehicle_entry_code
 * @property string $trip_code
 * @property string $grn_no
 * @property string $receipt_at
 * @property string $vehicle_entry_date
 * @property string $vehicle_code
 * @property string $arrival_time
 * @property string $gross_weight
 * @property string $tare_weight
 * @property string $tare_weight_time
 * @property string $qty
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $customer_code
 * @property integer $customer_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMilkVehicleEntry extends \app\models\ChildModel {

    public $vehicle, $customer_name, $bmc_ref_code, $bmc_name, $ref_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [    
            [['union_code', 'receipt_at', 'arrival_time', 'tare_weight_time', 'gross_weight', 'tare_weight', 'qty', 'receipt_at_code', 'dispatch_from', 'dispatch_from_code', 'receipt_datetime', 'receipt_shift_code'], 'required', 'except' => ['androidsync', 'importCsv']],[['milk_vehicle_entry_code', 'trip_code', 'grn_no', 'receipt_at', 'vehicle_code', 'qty', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_code', 'customer_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
            [['vehicle_entry_date', 'arrival_time', 'tare_weight_time', 'created_at', 'updated_at', 'receipt_at_code', 'dispatch_from', 'dispatch_from_code', 'receipt_datetime', 'receipt_shift_code', 'tanker_no', 'plant_code', 'mcc_plant_code'], 'safe'],
            [['gross_weight', 'tare_weight'], 'number'],
            [['originating_type'], 'integer'],
            [['mcc_plant_code', 'bmc_code', 'customer_code', 'customer_type', 'receipt_at'], 'required', 'when' => function ($model) {
                    return $model->receipt_at == 'VENDOR';
                }, 'whenClient' => "function (attribute, value) {
              return $('#tblmilkvehicleentry-receipt_at').val() == 'VENDOR';
          }", 'except' => ['androidsync', 'importCsv']],
            [['trip_code'], 'validateBottle', 'except' => ['androidsync', 'importCsv']],
            [['tare_weight_time'], 'validateTime', 'except' => ['androidsync', 'importCsv']],
            [['plant_code'], 'ValidateTripCode', 'skipOnError' => true, 'on' => 'importCsv'],
            [['vehicle_code'], 'validateTrip', 'skipOnError' => true],
            [['receipt_at'], 'AddBmcCode'],
            [['tanker_no'], 'required', 'when' => function ($model) {
                    return $model->dispatch_from == 'PARTY';
                }],
            [['vehicle_code'], 'required', 'when' => function ($model) {
                    return !($model->dispatch_from == 'PARTY');
                }],
            [['tanker_no'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false,],
            [['receipt_datetime'], 'CheckDateValidation', 'skipOnError' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_vehicle_entry_code' => Yii::t('app', 'Milk Vehicle Entry Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'grn_no' => Yii::t('app', 'Grn No.'),
            'receipt_at' => Yii::t('app', 'Destination Type'),
            'vehicle_entry_date' => Yii::t('app', 'Vehicle Entry Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'arrival_time' => Yii::t('app', 'Arrival Time'),
            'gross_weight' => Yii::t('app', 'Gross Weight'),
            'tare_weight' => Yii::t('app', 'Tare Weight'),
            'tare_weight_time' => Yii::t('app', 'Tare Weight Time'),
            'qty' => Yii::t('app', 'Qty'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'customer_code' => Yii::t('app', 'Name'),
            'customer_type' => Yii::t('app', 'Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'receipt_at_code' => Yii::t('app', 'Destination Name'),
            'dispatch_from' => Yii::t('app', 'Source Type'),
            'dispatch_from_code' => Yii::t('app', 'Source Name'),
            'receipt_datetime' => Yii::t('app', 'Receipt Datetime'),
            'receipt_shift_code' => Yii::t('app', 'Receipt Shift'),
            'tanker_no' => Yii::t('app', 'Tanker No'),
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

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getTripCode() {
        return $this->hasOne(TblVehicleTrip::className(), ['trip_code' => 'trip_code'])->andOnCondition(['IN', 'trip_status', ['open', 'tankerfull']]);
    }

    public function getCustomerCodeSource() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'dispatch_from_code']);
    }
    
    public function getCustomerCodeDest() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'receipt_at_code']);
    }

    public function getBmcCodeSource() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'dispatch_from_code']);
    }
    
    public function getBmcCodeDest() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'receipt_at_code']);
    }

    public function getMccPlantCodeSource() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'dispatch_from_code']);
    }
    
     public function getMccPlantCodeDest() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'receipt_at_code']);
    }

    public function getPlantCodeSource() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'dispatch_from_code']);
    }

    public function getPlantCodeDest() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'receipt_at_code']);
    }
    
    public function getPartyMasterCodeSource() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'dispatch_from_code']);
    }
    
    public function getPartyMasterCodeDest() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'receipt_at_code']);
    }
    
    public function validateBottle($attribute, $params) {
        $bottleCount = Yii::$app->general->getUnionConfiguration($this->union_code, 'receipt_sample_testing_count', 'PORTAL');
        $sampleBottle = TblSampleBottleTesting::find()->where(['trip_code' => $this->trip_code])->count();
        if ($sampleBottle < $bottleCount) {
            $this->addError('trip_code', "Receipt Not Allow (Receipt Sample Testing Count mismatch).");
        }
    }

    public function validateTime($attribute, $params) {
        if (!empty($this->tare_weight_time) || !empty($this->arrival_time)) {
            $tare = explode(':', $this->tare_weight_time);
            $arrival = explode(':', $this->arrival_time);
            if (($tare[0] > 23 || $tare[1] > 59) || ($arrival[0] > 23 || $arrival[1] > 59)) {
                $this->addError($attribute, Yii::t('app', 'Time is Invalid'));
            }
        }
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function getPrimaryCode($model, $autoInc = 1) {
        $primaryKey = $model->tableSchema->primaryKey[0];
        $organizations_code = !empty(Yii::$app->session->get('organizations_code')) ? Yii::$app->session->get('organizations_code') : $model->originating_org_code;
        $orgCode = 'PORTAL-' . $organizations_code . '-';
        $len = strlen($orgCode);
        $val = $model->find()
                ->select(["MAX(CONVERT(INT,substring(" . $primaryKey . ", " . $len . " +1,4))) AS " . $primaryKey])
                ->where("SUBSTRING(" . $primaryKey . ", 1," . $len . ")='" . trim($orgCode) . "'")
                ->one();
        $code1 = (int) $val[$primaryKey] + $autoInc;
        $value = $orgCode . $code1;

        return $value;
    }

    public function getTransactionCode($model, $primaryCode, $autoInc = 1) {
        $primaryKey = $model->tableSchema->primaryKey[0];
        $orgCode = $primaryCode . 'T';
        $len = strlen($orgCode);
        $val = $model->find()
                ->select(["MAX(CONVERT(INT,substring(" . $primaryKey . ", " . $len . " +1,4))) AS " . $primaryKey])
                ->where("SUBSTRING(" . $primaryKey . ", 1," . $len . ")='" . trim($orgCode) . "'")
                ->one();
        $code1 = (int) $val[$primaryKey] + $autoInc;
        $value = $orgCode . $code1;
        return $value;
    }

    public function ValidateTripCode() {
        $check_record = $this->find()->where(['grn_no' => $this->grn_no, 'vehicle_entry_date' => $this->vehicle_entry_date, 'plant_code' => $this->plant_code])->all();
        if (count($check_record) == 1) {
            $check_trip = TblVehicleTripDetail::find()
                    ->select(['tbl_vehicle_trip.trip_code', 'tbl_vehicle_trip.vehicle_code'])
                    ->distinct()
                    ->joinWith(['tripCode'])
                    ->where(['tbl_vehicle_trip.trip_code' => $this->trip_code]);
            if ($check_record[0]->receipt_at == 'PLANT') {
                $check_trip->andWhere(['tbl_vehicle_trip_detail.destination_type' => 'plant', 'tbl_vehicle_trip_detail.destination_code' => $this->plant_code]);
            }
            $check_trip = $check_trip->all();
            if (count($check_trip) == 0) {
                $this->addError('to_date', Yii::t('app/validation', 'Invalid Trip Code.'));
            } else {
                $this->vehicle_code = $check_trip[0]->vehicle_code;
            }
        } else {
            $this->addError('to_date', Yii::t('app/validation', 'Invalid Combination of Plant Code/Vehicle Entry Date/Grn No.'));
        }
    }

    public function getTripCodeAll() {
        return $this->hasOne(TblVehicleTrip::className(), ['trip_code' => 'trip_code']);
    }

    public function validateTrip($attribute) {
        $attribute = 'trip_code';
        $requiredConditions = [
            ($this->dispatch_from == 'BMC' && $this->receipt_at == 'PLANT'),
            ($this->dispatch_from == 'BMC' && $this->receipt_at == 'BMC'),
            ($this->dispatch_from == 'BMC' && $this->receipt_at == 'PARTY'),
        ];
        if (in_array(true, $requiredConditions)) {
            if (empty($this->trip_code)) {
                $this->addError($attribute, 'Trip Code required for selected Source & Destination .');
            }
        }
    }

    public function AddBmcCode($attribute, $params) {
        if (!empty($this->receipt_at_code)) {
            if ($this->receipt_at == 'BMC') {
                $Plant_code = TblPlant::find()
                        ->innerJoin('tbl_mcc_plant', 'tbl_plant.plant_code = tbl_mcc_plant.plant_code')
                        ->innerJoin('tbl_bmc', 'tbl_mcc_plant.mcc_plant_code = tbl_bmc.mcc_plant_code')
                        ->where(['tbl_bmc.bmc_code' => $this->receipt_at_code])
                        ->one();

                $Mcc_Plant_code = TblMccPlant::find()
                        ->where(['mcc_plant_code' => TblDcsBmc::findOne(['bmc_code' => $this->receipt_at_code])->mcc_plant_code])
                        ->one();

                if (!empty($Plant_code)) {
                    $this->plant_code = $Plant_code->plant_code;
                }
                if (!empty($Mcc_Plant_code)) {
                    $this->mcc_plant_code = $Mcc_Plant_code->mcc_plant_code;
                }
                $this->bmc_code = $this->receipt_at_code;
            } elseif ($this->receipt_at == 'PLANT') {
                $this->plant_code = $this->receipt_at_code;
            }
        }
    }

    public function CheckDateValidation($attribute, $params) {
        $this->receipt_datetime = date('Y-m-d', strtotime($this->receipt_datetime)) . ' ' . \Yii::$app->general->getshift($this->receipt_shift_code) . '.000000';
        $stock_date = TblBmcDispatchStock::find()->where(['bmc_code' => $this->bmc_code])->orderBy(['to_date' => SORT_DESC])->one();
        if (!empty($stock_date) && $this->receipt_at == 'BMC') {
            $dispatch_date = ($stock_date->type == 'dispatch') ? date('Y-m-d H:i:s', strtotime('+12 hours', strtotime($stock_date->to_date))) : $stock_date->to_date;
            $dispatch_date .= '.000000';
            if ($this->receipt_datetime < $dispatch_date) {
                $this->addError($attribute, Yii::t('app/validation', 'Receipt Datetime & shift must be greater than last stock entry.'));
                return FALSE;
            }
        } 
        return TRUE;
    }

}
