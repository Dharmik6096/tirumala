<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\syncutility\models\TblSentbox;
use yii\base\UserException;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use app\modules\tankermovement\models\TblVehicleTrip;

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
                [['source_org_code', 'source_org_type'], 'required'],
                [['destination_code', 'destination_type'], 'required', 'except' => ['on_crete_trip', 'gate-in', 'gate-out', 'autoTrip']],
                [['vehicle_trip_detail_code', 'vehicle_trip_code', 'vehicle_code', 'trip_code', 'challan_no', 'destination_code', 'destination_type', 'source_org_code', 'source_org_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'in_remarks', 'out_remarks', 'is_virtual_location'], 'safe'],
                [['transaction_datetime', 'arrival_time', 'departure_time', 'created_at', 'updated_at', 'is_last_destination', 'sequence_no'], 'safe'],
                [['travel_km', 'originating_type', 'is_active'], 'safe'],
                [['is_last_destination', 'is_virtual_location'], 'default', 'value' => 0],
                [['is_active'], 'default', 'value' => 1],
                [['arrival_time'], 'required', 'on' => ['gate-in']],
                [['departure_time'], 'required', 'on' => ['gate-out']],
                [['arrival_time'], 'validateArrival'],
                [['departure_time'], 'validateDeparture'],
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
            'in_remarks' => Yii::t('app', 'Remarks'),
            'out_remarks' => Yii::t('app', 'Remarks'),
            'is_virtual_location' => Yii::t('app', 'Is Vertual Location'),
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

    public function getPartyMasterCodeSource() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'source_org_code']);
    }

    public function getPartyMasterCodeDest() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'destination_code']);
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

    public function getOpenTripList($bmc_code, $vehicle_code, $transaction_date, $tripCode = '', $type = '', $tankerMovementWithTripSubStatus = '') {
        $transaction_date = date('Y-m-d', strtotime($transaction_date));
        $query = TblVehicleTripDetail::find()
                ->select(['tbl_vehicle_trip.trip_code'])
                ->distinct()
                ->joinWith(['tripCode'])
                ->andFilterWhere(['tbl_vehicle_trip.vehicle_code' => $vehicle_code]);
        if ($bmc_code == 'receipt') {
            $query->andWhere(['<=', 'tbl_vehicle_trip.transaction_date', $transaction_date]);
            if ($tripCode != 'alltrip') {
                $query->andWhere(['tbl_vehicle_trip.trip_status' => ['open', 'tankerfull']]);
            }
        } else if ($bmc_code == 'alltrip') {
            $to_date = !empty($tripCode) ? date('Y-m-d', strtotime($tripCode)) : $transaction_date;
            $query->andWhere(['>=', 'tbl_vehicle_trip.transaction_date', $transaction_date]);
            $query->andWhere(['<=', 'tbl_vehicle_trip.transaction_date', $to_date]);
        } else if (!empty($type) && $tankerMovementWithTripSubStatus && $tripCode != 'alltrip') {
            $query->andWhere(['tbl_vehicle_trip.trip_status' => ['generated', 'open'], 'tbl_vehicle_trip_detail.source_org_type' => $type, 'tbl_vehicle_trip_detail.source_org_code' => $bmc_code])
                    ->andWhere(['<=', 'tbl_vehicle_trip.transaction_date', $transaction_date])
                    ->andWhere(['IS NOT', 'arrival_time', null])
                    ->andWhere(['IS', 'departure_time', null]);
        } else {
            if ($tripCode != 'alltrip') {
                $query->andWhere(['tbl_vehicle_trip.trip_status' => ['generated', 'open']]);
            }
        }
        if (!empty($tripCode) && $bmc_code != 'alltrip' && $tripCode != 'alltrip') {
            $query->orWhere(['tbl_vehicle_trip.trip_code' => $tripCode])
                    ->andFilterWhere(['tbl_vehicle_trip.vehicle_code' => $vehicle_code]);
        }
        $data = $query->all();
        return ArrayHelper::map($data, 'trip_code', 'trip_code');
    }

    public function getOpenTripDetailList($union_code, $trip_process, $vehicle_code = '', $plants = '') {
        $query = TblVehicleTripDetail::find()->alias('vtd')
                ->select(['vt.trip_code', 'vt.vehicle_code', 'v.parsing_no'])
                ->distinct()
                ->innerJoin('tbl_vehicle_trip as vt', 'vt.trip_code = vtd.trip_code')
                ->innerJoin('tbl_vehicle_master as v', 'v.vehicle_code = vt.vehicle_code')
                ->where(['vt.union_code' => $union_code, 'vt.is_active' => 1]);

        if ($trip_process == 'milk_entry_qlty') {
            $plants = !empty(Yii::$app->session->get('Plant')) ? explode(',', Yii::$app->session->get('Plant')) : NULL;
            $query->andWhere(['vt.trip_status' => ['open', 'tankerfull'], 'vt.trip_sub_status' => 'plant_lot_pending']);
            $query->andWhere(['<=', 'vt.transaction_date', date('Y-m-d H:i:s')]);
            if (!empty($plants)) {
                $query->andWhere(['vtd.is_last_destination' => 1, 'vtd.source_org_type' => 'plant', 'vtd.source_org_code' => $plants]);
            }
        } else if (in_array($trip_process, ['cleaning_inspection', 'qa_inspection'])) {
            $subStatus = $trip_process == 'cleaning_inspection' ? 'cleaning_pending' : 'qa_pending';
            $query->andWhere(['vt.trip_status' => 'closed', 'vt.trip_sub_status' => $subStatus]);
        } else if ($trip_process == 'milk_entry_qlty_merge') {
            $query->andWhere(['BETWEEN', 'vt.transaction_date', date('Y-m-d', strtotime('-2 days')), date('Y-m-d')]);
            if (!empty($plants)) { //AMCS Req. for GetQltyTripList
                $query->andWhere(['vt.trip_status' => ['open', 'tankerfull', 'closed'], 'vt.trip_sub_status' => ['plant_lot_pending', 'cleaning_pending']]);
            } else {
                $query->andWhere(['vt.trip_status' => 'closed', 'vt.trip_sub_status' => 'cleaning_pending']);
            }
            $plants = empty($plants) ? (!empty(Yii::$app->session->get('Plant')) ? explode(',', Yii::$app->session->get('Plant')) : NULL) : $plants;
            if (!empty($plants)) {
                $query->andWhere(['vtd.is_last_destination' => 1, 'vtd.source_org_type' => 'plant', 'vtd.source_org_code' => $plants]);
            }
        }

        if (!empty($vehicle_code)) {
            $query->andWhere(['vt.vehicle_code' => $vehicle_code]);
        }
        return $query->asArray()->all();
    }

    public function getTripDetails($tankerMovementWithTripSubStatus) {
        $query = $this->find()->alias('td')->select(['td.destination_type', 'td.destination_code', 't.is_auto_trip', 'td.is_last_destination', 'td.arrival_time', 'td.vehicle_trip_detail_code'])
                ->leftJoin('tbl_vehicle_trip t', 't.trip_code = td.trip_code')
                ->where(['td.trip_code' => $this->trip_code, 'td.source_org_type' => $this->source_org_type, 'td.source_org_code' => $this->source_org_code])
                ->andWhere(['IS', 'td.challan_no', NULL]);

        if ($tankerMovementWithTripSubStatus) {
            $query->andWhere(['IS NOT', 'td.arrival_time', null])
                    ->andWhere(['IS', 'td.departure_time', null]);
        }

        return $query->orderBy(['td.sequence_no' => SORT_ASC])
                        ->asArray()
                        ->one();
    }

    public function getMaxCode($vehicleTripCode) {
        $prefixToRemove = $vehicleTripCode . 'T';
        $prefixLength = strlen($prefixToRemove);
        $maxCode = $this->find()
                ->select([
                    'max_number' => new Expression("MAX(CAST(SUBSTRING(vehicle_trip_detail_code, {$prefixLength} + 1, LEN(vehicle_trip_detail_code)) AS INT))"),
                ])
                ->where(['like', 'vehicle_trip_detail_code', $prefixToRemove . '%', false])
                ->andWhere(new Expression("LEN(vehicle_trip_detail_code) > {$prefixLength}"))
                ->scalar();
        return $maxCode + 1;
    }

    public function getTripData() {
        return $this->find()
                        ->select(['source_org_type', 'source_org_code'])
                        ->where(['trip_code' => $this->trip_code])->andWhere(['!=', 'is_last_destination', 1])
                        ->orderBy(['sequence_no' => SORT_DESC])
                        ->one();
    }

    public function validateArrival($attribute, $params) {
        $prevTrip = self::find()
                ->where(['<', 'sequence_no', $this->sequence_no])
                ->andWhere(['vehicle_trip_code' => $this->vehicle_trip_code])
                ->orderBy(['sequence_no' => SORT_DESC])
                ->one();
        if ($prevTrip && !empty($prevTrip->departure_time) && strtotime($this->arrival_time) <= strtotime($prevTrip->departure_time)) {
            $this->addError($attribute, 'Arrival time must be greater than previous departure time.');
        }
    }

    public function validateDeparture($attribute, $params) {
        if (!empty($this->arrival_time) && strtotime($this->departure_time) <= strtotime($this->arrival_time)) {
            $this->addError($attribute, 'Departure time must be greater than Arrival time.');
        }
    }

    public function setChildTable(&$model, &$modelSave, &$childModel) {
        $visibility_status = 0;
        $content = $modelSave['content'];
        $action_datetime = $content['action_datetime'];
        $remarks = $content['remarks'];
        $action_type = $content['action_type'];
        $model->scenario = 'gate-' . $action_type;
        $isValid = FALSE;
        $trip = TblVehicleTrip::findOne($model->vehicle_trip_code);
        if ($action_type == 'in' && empty($model->arrival_time)) {
            $isValid = TRUE;
            $model->in_remarks = $remarks;
            $model->arrival_time = $action_datetime;
            $trip->trip_sub_status = $model->is_last_destination ? 'plant_lot_pending' : 'gate_in';
            if ($model->is_virtual_location == 1) {
                $model->departure_time = $departure = date('Y-m-d H:i:s', strtotime($action_datetime) + 1);
                $nextTripDetail = TblVehicleTripDetail::find()
                        ->where(['vehicle_trip_code' => $model->vehicle_trip_code])
                        ->andWhere(['>', 'sequence_no', $model->sequence_no])
                        ->orderBy(['sequence_no' => SORT_ASC])
                        ->one();
                if (!empty($nextTripDetail)) {
                    $nextTripDetail->scenario = 'gate-' . $action_type;
                    $nextTripDetail->arrival_time = date('Y-m-d H:i:s', strtotime($departure) + 1);
                }
            }
            $visibility_status = $model->is_last_destination ? 1 : 2;
        } else if ($action_type == 'out' && empty($model->departure_time)) {
            $isValid = TRUE;
            $model->departure_time = $action_datetime;
            $model->out_remarks = $remarks;
            $trip->trip_sub_status = 'gate_out';
            $visibility_status = (empty($model->arrival_time) || $model->sequence_no == 1) ? 1 : 0;
            if (empty($model->arrival_time)) {
                $model->arrival_time = date('Y-m-d H:i:s', strtotime($model->departure_time) - 1);
            }
        }
        if ($isValid && $model->validate()) {
            $trip->sub_status_time = $action_datetime;
            $childModel[] = $trip;
            if (isset($nextTripDetail)) {
                $childModel[] = $nextTripDetail;
            }
            $response = Yii::$app->general->getColumnName($model->source_org_type);
            if (!empty($response['rel'])) {
                $sourceData = $model->{$response['rel'] . 'Source'};
                $remarks = $sourceData->{$response['ref_code']} . '-' . $sourceData->{$response['name']} . '-' . $remarks;
            }
            $trackingDetail = ['visibility_status' => $visibility_status, 'module_code' => $model->vehicle_trip_detail_code, 'module_type' => 'tbl_vehicle_trip_detail'];
            Yii::$app->general->setVehicleTripTrackingDetail($trip, $trackingDetail, $remarks);
        }
    }

    public static function getTripDispatchDetail($trip_code, $bmc_code) {
        return self::find()->alias('td')
            ->select([
                'td.destination_type', 'td.destination_code', 'td.is_last_destination', 'td.arrival_time', 'td.vehicle_trip_detail_code',
                't.vehicle_code', 't.trip_code', 't.transaction_date', 't.union_code', 't.is_auto_trip',
                't.driver_name', 't.mobile_no', 'v.parsing_no'
            ])
            ->innerJoin('tbl_vehicle_trip t', 't.trip_code = td.trip_code')
            ->innerJoin('tbl_vehicle_master v', 'v.vehicle_code = t.vehicle_code')
            ->where([
                'td.trip_code' => $trip_code,
                'td.source_org_type' => 'bmc',
                'td.source_org_code' => $bmc_code,
                't.trip_status' => ['generated', 'open']
            ])
            ->andWhere(['IS', 'td.challan_no', NULL])
            ->andWhere(['IS NOT', 'td.arrival_time', null])
            ->andWhere(['IS', 'td.departure_time', null])
            ->orderBy(['td.sequence_no' => SORT_ASC])
            ->asArray()
            ->one();
    }

}
