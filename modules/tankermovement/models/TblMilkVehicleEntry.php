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

    public $vehicle, $customer_name;

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
            [['trip_code', 'union_code', 'plant_code', 'vehicle_entry_date', 'receipt_at', 'arrival_time', 'tare_weight_time', 'gross_weight', 'tare_weight', 'qty', 'vehicle_code'], 'required'],
            [['milk_vehicle_entry_code', 'trip_code', 'grn_no', 'receipt_at', 'vehicle_code', 'qty', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_code', 'customer_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
            [['vehicle_entry_date', 'arrival_time', 'tare_weight_time', 'created_at', 'updated_at'], 'safe'],
            [['gross_weight', 'tare_weight'], 'number'],
            [['originating_type'], 'integer'],
            [['mcc_plant_code', 'bmc_code', 'customer_code', 'customer_type', 'receipt_at'], 'required', 'when' => function ($model) {
                    return $model->receipt_at == 'VENDOR';
                }, 'whenClient' => "function (attribute, value) {
              return $('#tblmilkvehicleentry-receipt_at').val() == 'VENDOR';
          }"],
            [['trip_code'], 'validateBottle'],
            [['tare_weight_time'], 'validateTime'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_vehicle_entry_code' => Yii::t('app', 'Milk Vehicle Entry Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'receipt_at' => Yii::t('app', 'Receipt At'),
            'vehicle_entry_date' => Yii::t('app', 'Vehicle Entry Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
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

    public function validateBottle($attribute, $params) {
        $bottleCount = Yii::$app->general->getUnionConfiguration($this->union_code, 'sample_bottle_testing', 'PORTAL');
        $sampleBottle = TblSampleBottleTesting::find()->where(['trip_code' => $this->trip_code])->count();
        if ($sampleBottle < $bottleCount) {
            $this->addError('trip_code', "Receipt Not Allow.");
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

}
