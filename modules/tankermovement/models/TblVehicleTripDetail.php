<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\syncutility\models\TblSentbox;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_vehicle_trip_detail".
 *
 * @property string $vehicle_trip_detail_code
 * @property string $vehicle_trip_code
 * @property string $vehicle_code
 * @property string $trip_code
 * @property string $challan_no
 * @property string $transaction_datetime
 * @property string $destination_code
 * @property string $destination_type
 * @property string $source_org_code
 * @property string $source_org_type
 * @property string $arrival_time
 * @property string $departure_time
 * @property string $travel_km
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
class TblVehicleTripDetail extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_trip_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['destination_code', 'destination_type', 'source_org_code', 'source_org_type'], 'required'],
                [['vehicle_trip_detail_code', 'vehicle_trip_code', 'vehicle_code', 'trip_code', 'challan_no', 'destination_code', 'destination_type', 'source_org_code', 'source_org_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['transaction_datetime', 'arrival_time', 'departure_time', 'created_at', 'updated_at', 'is_last_destination'], 'safe'],
                [['travel_km', 'originating_type', 'is_active'], 'safe'],
                [['is_last_destination'], 'default', 'value' => 0],
                [['is_active'], 'default', 'value' => 1],
                //   [['destination_code'], 'unique', 'targetAttribute' => ['trip_code', 'destination_code', 'destination_type'], 'message' => Yii::t('app/validation', 'Trip for BMC has been already taken.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vehicle_trip_detail_code' => Yii::t('app', 'Vehicle Trip Detail Code'),
            'vehicle_trip_code' => Yii::t('app', 'Vehicle Trip Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'transaction_datetime' => Yii::t('app', 'Datetime'),
            'destination_code' => Yii::t('app', 'Destination Code'),
            'destination_type' => Yii::t('app', 'Destination'),
            'source_org_code' => Yii::t('app', 'Source Code'),
            'source_org_type' => Yii::t('app', 'Source'),
            'arrival_time' => Yii::t('app', 'Arrival Time'),
            'departure_time' => Yii::t('app', 'Departure Time'),
            'travel_km' => Yii::t('app', 'Travel Km'),
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

    public function getTripCode() {
        return $this->hasOne(TblVehicleTrip::className(), ['trip_code' => 'trip_code']);
    }

    public function getTripDetailEntry() {
        $model = TblVehicleTrip::findOne(['trip_code' => $this->trip_code]);
        $last_trip = TblVehicleTripDetail::find()
                ->where(['trip_code' => $this->trip_code])
                ->orderBy(['transaction_datetime' => SORT_DESC])
                ->one();
        $this->source_org_code = $last_trip->destination_code;
        $this->source_org_type = $last_trip->destination_type;
        $this->vehicle_trip_code = $model->vehicle_trip_code;
        $this->vehicle_code = $model->vehicle_code;
        $this->transaction_datetime = date('Y-m-d H:i:s');
        $this->trip_code = $model->trip_code;
        $vehicle_trip_detail_code = $model->vehicle_trip_code . 'T' . (((int) substr($last_trip->vehicle_trip_detail_code, strlen($model->vehicle_trip_code) + 1)) + 1);
        $this->vehicle_trip_detail_code = $vehicle_trip_detail_code;
        if ($this->validate()) {
            return $this;
        }
        return FALSE;
    }

    public function getLastTrip($trip) {
        return $last_trip = $this->find()
                ->where(['trip_code' => $trip])
                ->orderBy(['transaction_datetime' => SORT_DESC])
                ->one();
    }

    public function afterSave($insert, $changedAttributes) {
        if (strtolower($this->source_org_type) == 'bmc') {
            $sentboxArray = [];
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->source_org_code, '', '', FALSE);
            foreach ($sentboxArray as $sent) {
                $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
                $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
                if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                    if (!($sentbox->setSentbox($this, $flag))) {
                        throw new UserException("SentBox Entry is not created so transaction is rollback!");
                    }
                }
            }
        }
    }

    public function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->originating_org_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function getOpenTripList($bmc_code, $vehicle_code, $transaction_date, $tripCode = '') {
        $transaction_date = date('Y-m-d', strtotime($transaction_date));
        $query = TblVehicleTripDetail::find()
                ->select(['tbl_vehicle_trip.trip_code'])
                ->distinct()
                ->joinWith(['tripCode'])
                ->where(['tbl_vehicle_trip.transaction_date' => $transaction_date])
                ->andWhere(['tbl_vehicle_trip.trip_status' => ['generated', 'open']])
                ->andWhere(['tbl_vehicle_trip_detail.vehicle_code' => $vehicle_code, 'tbl_vehicle_trip_detail.source_org_type' => 'bmc', 'tbl_vehicle_trip_detail.source_org_code' => $bmc_code]);
        if (!empty($tripCode)) {
            $query->orWhere(['tbl_vehicle_trip.trip_code' => $tripCode]);
        }
        $data = $query->all();
        return ArrayHelper::map($data, 'trip_code', 'trip_code');
    }

}
