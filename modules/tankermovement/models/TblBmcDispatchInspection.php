<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripDetail;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\dcsoperation\models\TblShift;
use app\modules\tankermovement\models\TblConfigTxnResult;

/**
 * This is the model class for table "tbl_bmc_dispatch_inspection".
 *
 * @property string $bmc_dispatch_inspection_code
 * @property string $inspection_date
 * @property integer $shift_code
 * @property string $vehicle_code
 * @property string $trip_code
 * @property string $remarks
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
class TblBmcDispatchInspection extends \app\models\ChildModel {

    public $vehicle_trip_detail_code, $config_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_dispatch_inspection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['shift_code', 'inspection_date', 'vehicle_code', 'trip_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required'],
            [['bmc_dispatch_inspection_code', 'vehicle_code', 'trip_code', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['inspection_date', 'created_at', 'updated_at'], 'safe'],
            [['shift_code', 'originating_type'], 'safe'],
            [['union_code'], 'required', 'except' => ['androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_dispatch_inspection_code' => Yii::t('app', 'Bmc Dispatch Inspection Code'),
            'inspection_date' => Yii::t('app', 'Inspection Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'vehicle_code' => Yii::t('app', 'Vehicle No.'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'remarks' => Yii::t('app', 'Remarks'),
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

    public function getTripCode() {
        return $this->hasOne(TblVehicleTrip::className(), ['trip_code' => 'trip_code']);
    }

    public function getTripDetailCode() {
        return TblVehicleTripDetail::find()->where(['vehicle_trip_detail_code' => $this->vehicle_trip_detail_code])->one();
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getConfigResult() {
        return TblConfigTxnResult::findOne(['ref_code' => $this->bmc_dispatch_inspection_code, 'config_code' => $this->config_code, 'config_for' => 'BMC_DISPATCH_INSPECTION']);
        //return $this->hasOne(TblConfigTxnResult::className(), ['ref_code' => 'bmc_dispatch_inspection_code'])->andWhere(['config_code' => $this->config_code, 'config_for' => 'BMC_DISPATCH_INSPECTION']);
    }

    public function afterSave($insert, $changedAttributes) {
        $model = TblVehicleTrip::findOne(['trip_code' => $this->trip_code]);
        if (!empty($model) && $model->trip_status == 'generated') {
            $model->trip_status = 'open';
            $model->save();
        }
    }

}
