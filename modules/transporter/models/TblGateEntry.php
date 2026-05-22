<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\transporter\models\TblTransporter;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_gate_entry".
 *
 * @property integer $gate_entry_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $route_code
 * @property string $transporter_code
 * @property string $vehicle_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property string $define_arrival_time
 * @property string $actual_arrival_time
 * @property string $grace_time
 * @property string $late_by_time
 * @property integer $responsibility_code
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
class TblGateEntry extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_gate_entry';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'date_time_of_collection', 'shift_code', 'actual_arrival_time', 'route_code', 'vehicle_code'], 'required', 'except' => ['getOut', 'androidsync']],
            [['date_time_of_collection', 'define_arrival_time', 'actual_arrival_time', 'created_at', 'updated_at', 'status', 'status_time'], 'safe'],
            [['shift_code', 'responsibility_code', 'originating_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['dcs_code', 'transporter_code', 'created_by', 'updated_by'], 'safe'],
            [['grace_time', 'late_by_time', 'no_of_filled_can', 'no_of_empty_can'], 'number', 'except' => ['getOut']],
            [['actual_arrival_time'], 'date', 'format' => 'php:H:i', 'except' => ['getOut', 'androidsync']],
//            [['route_code'], 'unique', 'targetAttribute' => ['route_code', 'date_time_of_collection', 'shift_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['getOut']],
            [['route_code'], 'unique', 'targetAttribute' => ['route_code', 'date_time_of_collection', 'shift_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function ($model) {
                    $client_code = \Yii::$app->session->get('eiplCode');
                    return (!(in_array($client_code, ['UMANG', 'MOTHER'])));
                }, 'except' => ['getOut']],
            [['route_code'], function ($attribute, $params) {
                    $client_code = \Yii::$app->session->get('eiplCode');
                    if (empty($this->getErrors()) && (!(in_array($client_code, ['UMANG', 'MOTHER'])))) {
                        Yii::$app->general->shiftLock($this, 'date_time_of_collection', 'mcc_plant_code', 'date_time_of_collection', 'vm_data_lock');
                    }
                }, 'skipOnEmpty' => TRUE, 'except' => ['getOut']],
            [['status'], 'default', 'value' => 0],
            [['vehicle_code'], 'validateVehicle', 'except' => ['getOut']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'gate_entry_code' => Yii::t('app', 'Gate Entry Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'route_code' => Yii::t('app', 'Route'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'date_time_of_collection' => Yii::t('app', 'Date Of Collection'),
            'shift_code' => Yii::t('app', 'Shift'),
            'define_arrival_time' => Yii::t('app', 'Defined Time'),
            'actual_arrival_time' => Yii::t('app', 'Arrival Time'),
            'grace_time' => Yii::t('app', 'Grace Time'),
            'late_by_time' => Yii::t('app', 'Late By Time'),
            'responsibility_code' => Yii::t('app', 'Responsibility Code'),
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
            'no_of_filled_can' => Yii::t('app', 'Can In'),
            'no_of_empty_can' => Yii::t('app', 'Can Out'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getTransporterCode() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getVehicleCode() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function getVehicleName() {
        return $this->hasOne(TblVehicleMaster::className(), ['parsing_no' => 'vehicle_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function validateVehicle($attribute, $params) {
        if (Yii::$app->session->get('eiplCode') != 'DODLA') {
            $data = $this->find()->where(['vehicle_code' => $this->vehicle_code])
                    ->andWhere(['status' => 0])
                    ->one();
            if (!empty($data) && empty($this->gate_entry_code)) {
                $this->addError('vehicle_code', Yii::t('app/validation', $this->getAttributeLabel('vehicle_code') . ' is Already Available.'));
            }
        }
    }

}
