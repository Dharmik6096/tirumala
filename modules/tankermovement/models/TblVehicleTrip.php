<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

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

    public $transporter_code, $is_last_destination;

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
            [['vehicle_code', 'transaction_date', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'required'],
            [['vehicle_trip_code', 'vehicle_code', 'trip_code', 'grn_no', 'trip_status', 'trip_for', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['transaction_date', 'created_at', 'updated_at', 'originating_type', 'transporter_code', 'is_last_destination', 'trip_mode'], 'safe'],
            [['trip_status'], 'default', 'value' => 'generated'],
            [['trip_for'], 'default', 'value' => 'bmcdispatch'],
            [['trip_mode'], 'default', 'value' => 'online'],
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
            'trip_status' => Yii::t('app', 'Status'),
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

    public function setModel() {
        $save_model = [];
        $validate = TRUE;
        $inspection = FALSE;
        $this->transaction_date = date('Y-m-d', strtotime($this->transaction_date));
        $this->originating_org_code = $this->union_code;
        $model = $this->find()->where(['vehicle_code' => $this->vehicle_code])
                ->andWhere(['!=', 'trip_status', 'closed'])
                ->orderBy(['transaction_date' => SORT_ASC])
                ->one();
        if (!empty($model)) {
            $last_trip_date = $model->transaction_date;
            if ($last_trip_date != $this->transaction_date) {
                $validate = FALSE;
                $this->addError('vehicle_code', Yii::t('app', 'First need to close Trip No. ' . $model->trip_code));
            } else if ($model->trip_status == 'tankerfull') {
                $validate = FALSE;
                $this->addError('vehicle_code', Yii::t('app', 'Tanker is Full Trip No. ' . $model->trip_code));
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
            $save_model[] = $this;
        }
        if ($validate) {
            $trip_detai = new TblVehicleTripDetail();
            $last_trip = TblVehicleTripDetail::find()
                    ->where(['vehicle_code' => $this->vehicle_code])
                    ->andWhere(['<=', 'CAST(transaction_datetime as date)', $this->transaction_date])
                    ->orderBy(['transaction_datetime' => SORT_DESC])
                    ->one();
            if (!empty($last_trip)) {
                $trip_detai->source_org_code = $last_trip->destination_code;
                $trip_detai->source_org_type = $last_trip->destination_type;
            } else {
                $trip_detai->source_org_code = $model->plant_code;
                $trip_detai->source_org_type = 'plant';
            }
            $trip_detai->originating_org_code = $this->union_code;
            $trip_detai->destination_code = $this->bmc_code;
            $trip_detai->destination_type = 'bmc';
            $trip_detai->vehicle_trip_code = $model->vehicle_trip_code;
            $trip_detai->vehicle_code = $model->vehicle_code;
            $trip_detai->transaction_datetime = date('Y-m-d H:i:s');
            $trip_detai->trip_code = $model->trip_code;
            $trip_detai->arrival_time = date('Y-m-d H:i:s');
            if (empty($vehicle_trip_detail_code)) {
                $vehicle_trip_detail_code = $trip_detai->vehicle_trip_code . 'T' . (((int) substr($last_trip->vehicle_trip_detail_code, strlen($trip_detai->vehicle_trip_code) + 1)) + 1);
            }
            $trip_detai->vehicle_trip_detail_code = $vehicle_trip_detail_code;
            if (!$trip_detai->validate()) {
                $validate = FALSE;
                $errors = $trip_detai->getErrors();
                if (isset($errors['destination_code'])) {
                    $this->addError('bmc_code', $errors['destination_code'][0]);
                }
            }
            $save_model[] = $trip_detai;
        }
        $api_response = [];
        if ($model->trip_mode == 'online') {
            $api_response['inspection_require'] = $inspection;
            $api_response['trip_code'] = $model->trip_code;
            $api_response['trip_status'] = $model->trip_status;
        }
        return [$validate, $save_model, $api_response];
    }

    public function generateTripCode() {
        $primaryKey = 'trip_code';
        $prefix = substr($this->vehicleCode->parsing_no, -4) . substr($this->bmc_code, -2);
        $len = strlen($prefix);
        $val = $this->find()
                ->select(["MAX(CONVERT(INT,substring(" . $primaryKey . ", " . $len . " +1,4))) AS " . $primaryKey])
                ->where("SUBSTRING(" . $primaryKey . ", 1," . $len . ")='" . trim($prefix) . "'")
                ->one();
        $code1 = (int) $val[$primaryKey] + 1;
        return $prefix . $code1;
    }

}
