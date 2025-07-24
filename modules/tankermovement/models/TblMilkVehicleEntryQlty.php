<?php

namespace app\modules\tankermovement\models;

use app\models\ChildModel;
use app\modules\globalmaster\models\TblVehicleType;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblVehicleMaster;
use Yii;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\tankermovement\models\TblVehicleTripDetail;
use app\modules\tankermovement\models\TblVehicleTrip;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_qlty".
 *
 * @property integer $milk_vehicle_entry_qlty_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $vehicle_code
 * @property string $arrival_datetime
 * @property string $trip_code
 * @property string $chamber_no
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $density
 * @property string $protein
 * @property string $lactose
 * @property string $freezing_point
 * @property string $mbrt
 * @property string $temp
 * @property string $acidity
 * @property string $status
 * @property string $status_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMilkVehicleEntryQlty extends ChildModel {

    public $config_code, $sample_time, $is_clr_input;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry_qlty';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
            [['arrival_datetime', 'status_datetime', 'created_at', 'updated_at', 'lot_datetime', 'lot_no', 'config_code', 'tested_by', 'verified_by', 'sample_datetime', 'record_status', 'sample_time', 'is_qty_only', 'is_pending_merge', 'is_approved', 'is_clr_input'], 'safe'],
            [['fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'], 'number'],
            [['chamber_no', 'sample_datetime', 'record_status'], 'required', 'except' => ['resetQlty']],
            [['sample_time'], 'required', 'on' => ['qltySubmit', 'update']],
            [['originating_type'], 'integer'],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code'], 'string', 'max' => 6],
            [['vehicle_code', 'trip_code'], 'string', 'max' => 20],
            [['chamber_no', 'status'], 'string', 'max' => 50],
            [['tested_by', 'verified_by'], 'string', 'max' => 100],
            [['sample_time'], function ($attribute, $params) {
                    Yii::$app->general->validateTime($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'on' => ['qltySubmit', 'update']],
            [['sample_datetime'], 'validateSampleAfterGrossWeight', 'on' => ['qltySubmit', 'update']],
            [['is_qty_only', 'is_pending_merge', 'is_approved'], 'default', 'value' => 1],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblMilkVehicleEntryQlty', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_vehicle_entry_qlty_code' => Yii::t('app', 'Milk Vehicle Entry Qlty Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'arrival_datetime' => Yii::t('app', 'Arrival Datetime'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'chamber_no' => Yii::t('app', 'Compartment No'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'clr' => Yii::t('app', 'CLR'),
            'water' => Yii::t('app', 'Water'),
            'density' => Yii::t('app', 'Density'),
            'protein' => Yii::t('app', 'Protein'),
            'lactose' => Yii::t('app', 'Lactose'),
            'freezing_point' => Yii::t('app', 'Freezing Point'),
            'mbrt' => Yii::t('app', 'Mbrt'),
            'temp' => Yii::t('app', 'Temp'),
            'acidity' => Yii::t('app', 'Acidity'),
            'status' => Yii::t('app', 'Status'),
            'status_datetime' => Yii::t('app', 'Status Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'lot_datetime' => Yii::t('app', 'Lot Datetime'),
            'lot_no' => Yii::t('app', 'Lot No'),
            'tested_by' => Yii::t('app', 'Tested By'),
            'verified_by' => Yii::t('app', 'Verified By'),
            'sample_datetime' => Yii::t('app', 'Sample Date'),
            'sample_time' => Yii::t('app', 'Time'),
            'record_status' => Yii::t('app', 'Record Status'),
        ];
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getVehicleType() {
        return $this->hasOne(TblVehicleType::className(), ['vehicle_type_code' => 'vehicle_type_code']);
    }

    public function getVehicle() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMilkVehicleEntryQlty($forAPI = FALSE) {
        $plantLotCreationInterval = Yii::$app->general->getCheckBmcConfiguration($this->union_code, 'plant_lot_creation_interval', $this->plant_code, 'PLANT', 'PLANT_RECEIPT_CONFIG');
        $plantLotCreationInterval = is_numeric($plantLotCreationInterval) ? (int) $plantLotCreationInterval : 0;
        if ($plantLotCreationInterval > 0) {
            $query = $this->find()
                    ->where(['trip_code' => $this->trip_code, 'union_code' => $this->union_code]);
            if ($forAPI) {
                $query->andWhere(['LOWER(status)' => 'done']);
            } else {
                $query->andWhere(['!=', 'LOWER(status)', 'discarded']);
            }
            $records = $query->all();
            if (!empty($records)) {
                $formattedRecordsForAPI = [];
                if ($forAPI) {
                    foreach ($records as $record) {
                        $formattedRecordsForAPI[] = [
                            'plant_code' => $record->plant_code,
                            'vehicle_code' => $record->vehicle_code,
                            'trip_code' => $record->trip_code,
                            'chamber_no' => $record->chamber_no,
                            'status' => $record->status,
                            'sample_datetime' => Yii::$app->controls->view_datetime($record->sample_datetime, 'php:Y-m-d H:i:s'),
                            'record_status' => $record->record_status,
                        ];
                    }
                } else {
                    $totalRecords = count($records);
                    $doneRecordsCount = 0;
                    $maxLotDatetime = NULL;
                    foreach ($records as $record) {
                        if ($record->status === 'done') {
                            $doneRecordsCount++;
                        }
                        $currentLotDatetime = strtotime($record->lot_datetime);
                        if ($maxLotDatetime === NULL || $currentLotDatetime > $maxLotDatetime) {
                            $maxLotDatetime = $currentLotDatetime;
                        }
                        $formattedRecordsForAPI[] = [
                            'chamber_no' => $record->chamber_no,
                            'sample_datetime' => Yii::$app->controls->view_datetime($record->sample_datetime, 'php:Y-m-d H:i:s'),
                        ];
                    }
                    $currentTime = time();
                    $intervalInSeconds = $plantLotCreationInterval * 3600;
                    if (($currentTime - $maxLotDatetime) > $intervalInSeconds || $totalRecords !== $doneRecordsCount) {
                        return ['success' => 0, 'record_data' => [], 'validation' => TRUE, 'lotQltyValidate' => TRUE, 'lotQltyData' => $formattedRecordsForAPI];
                    }
                }

                $formattedRecords = [];
                // foreach ($records as $record) {
                //     $formattedRecords[$record->chamber_no] = [
                //         'fat' => number_format($record->fat, 2, '.', ''),
                //         'snf' => number_format($record->snf, 2, '.', ''),
                //         'clr' => number_format($record->clr, 2, '.', ''),
                //         'water' => number_format($record->water, 2, '.', ''),
                //         'density' => number_format($record->density, 2, '.', ''),
                //         'protein' => number_format($record->protein, 2, '.', ''),
                //         'lactose' => number_format($record->lactose, 2, '.', ''),
                //         'freezing_point' => number_format($record->freezing_point, 2, '.', ''),
                //         'mbrt' => number_format($record->mbrt, 2, '.', ''),
                //         'temp' => number_format($record->temp, 2, '.', ''),
                //         'acidity' => number_format($record->acidity, 2, '.', ''),
                //     ];
                // }
                return ['success' => 1, 'record_data' => $formattedRecords, 'validation' => FALSE, 'lotQltyValidate' => TRUE, 'lotQltyData' => $formattedRecordsForAPI];
            } else {
                return ['success' => 0, 'record_data' => [], 'validation' => TRUE, 'lotQltyValidate' => TRUE, 'lotQltyData' => []];
            }
        }
        return ['success' => 0, 'record_data' => [], 'validation' => FALSE, 'lotQltyValidate' => FALSE, 'lotQltyData' => []];
    }

    public function getConfigResult() {
        return TblConfigTxnResult::findOne(['ref_code' => (string) $this->milk_vehicle_entry_qlty_code, 'config_code' => $this->config_code, 'config_for' => 'PLANT_QUALITY_RECEIPT', 'ref_table' => 'tbl_milk_vehicle_entry_qlty']);
    }

    public function getPlant($trip_code) {
        return TblVehicleTripDetail::find()->select(['source_org_code'])->where(['is_last_destination' => 1, 'source_org_type' => 'plant', 'trip_code' => $trip_code])->one();
    }

    public function getTripData($trip_code) {
        return TblVehicleTrip::find()->where(['trip_code' => $trip_code, 'trip_status' => ['open', 'tankerfull'], 'is_active' => 1])->count();
    }

    public function validateSampleAfterGrossWeight($attribute, $params) {
        if (empty($this->getErrors())) {
            $pk = $this->scenario === 'update' ? $this->milk_vehicle_entry_qlty_code : $this->chamber_no;            
            $milkVehicleEntryQltyData = $this->findOne($pk);
            $lotQltySampleTimeValidateConfig = Yii::$app->general->getUnionConfiguration($milkVehicleEntryQltyData->union_code, 'lot_qlty_sample_time_validate', 'PORTAL');
            $lotQltySampleTimeValidate = $lotQltySampleTimeValidateConfig = '' ? 1 : $lotQltySampleTimeValidateConfig;
            if (!empty($lotQltySampleTimeValidate)) {
                $lotQltySampleTimeValidateConfig = Yii::$app->general->getCheckBmcConfiguration($milkVehicleEntryQltyData->union_code, 'lot_qlty_sample_time_validate', $milkVehicleEntryQltyData->plant_code, 'PLANT', 'PLANT_RECEIPT_CONFIG');
                $lotQltySampleTimeValidate = $lotQltySampleTimeValidateConfig == '' ? 1 : $lotQltySampleTimeValidateConfig;
            }
            $sampleTime = date('H:i:s', strtotime($this->sample_time));
            $sampleDate = date('Y-m-d', strtotime($this->sample_datetime));
            $this->sample_datetime = $sampleDate . ' ' . $sampleTime;
            if (!empty($lotQltySampleTimeValidate)) {
                $milkVehicleEntryTxn = TblMilkVehicleEntryTransaction::find()
                        ->alias('mvet')
                        ->joinWith(['milkVehicleEntryCode mve'])
                        ->where([ 'mve.trip_code' => $milkVehicleEntryQltyData->trip_code])
                        ->orderBy(['mvet.gross_weight_time' => SORT_DESC])
                        ->one();

                if (!empty($milkVehicleEntryTxn) && !empty($milkVehicleEntryTxn->gross_weight_time)) {
                    $grossDate = date('Y-m-d', strtotime($milkVehicleEntryTxn->gross_weight_time));
                    $grossTime = date('H:i:s', strtotime($milkVehicleEntryTxn->gross_weight_time));
                    if ($sampleDate < $grossDate) {
                        $this->addError($attribute, 'Sample collection date must be equal or after gross weight date.');
                        return;
                    }
                    if ($sampleDate == $grossDate && $sampleTime <= $grossTime) {
                        $this->addError('sample_time', 'Sample collection time must be after gross weight time.');
                        return;
                    }
                } else {
                    $this->addError('sample_datetime', 'Gross weight is pending.');
                    return;
                }
            }
        }
    }

    public function GetClrInput() {
        $isClrInput = Yii::$app->general->getCheckBmcConfiguration($this->union_code, 'is_clr_input', $this->plant_code, 'PLANT', 'PLANT_RECEIPT_CONFIG');
        if ($isClrInput == '') {
            $isClrInput = Yii::$app->general->getUnionConfiguration($this->union_Code, 'is_clr_input', 'PORTAL');
        }
        $this->is_clr_input = $isClrInput;
    }

}
