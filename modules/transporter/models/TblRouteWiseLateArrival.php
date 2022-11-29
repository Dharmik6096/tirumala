<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\organisation\models\TblUnions;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\transporter\models\TblTransporter;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_route_wise_late_arrival".
 *
 * @property integer $arrival_code
 * @property string $route_code
 * @property string $transporter_code
 * @property string $vehicle_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property string $define_arrival_time
 * @property string $actual_arrival_time
 * @property string $grace_time
 * @property string $late_by_time
 * @property integer $responsibility
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblRouteWiseLateArrival extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_route_wise_late_arrival';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['route_code', 'transporter_code', 'vehicle_code', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
            [['date_time_of_collection', 'define_arrival_time', 'actual_arrival_time', 'created_at', 'updated_at', 'vts_arrival_time', 'vts_late_by_time'], 'safe'],
            [['shift_code', 'responsibility', 'originating_type'], 'integer'],
            [['grace_time', 'late_by_time'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'arrival_code' => Yii::t('app', 'Arrival Code'),
            'route_code' => Yii::t('app', 'Route'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'date_time_of_collection' => Yii::t('app', 'Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'define_arrival_time' => Yii::t('app', 'Define Arrival Time'),
            'actual_arrival_time' => Yii::t('app', 'Actual Arrival Time'),
            'grace_time' => Yii::t('app', 'Grace Time(minute)'),
            'late_by_time' => Yii::t('app', 'Late By Time(minute)'),
            'responsibility' => Yii::t('app', 'Responsibility'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'vts_arrival_time' => Yii::t('app', 'VTS Arrival Time'),
            'vts_late_by_time' => Yii::t('app', 'VTS Late By Time(minute)'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
        ];
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getTransporterCode() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getVehicle() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

}
