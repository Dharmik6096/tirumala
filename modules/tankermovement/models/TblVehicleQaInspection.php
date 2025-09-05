<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\organisation\models\TblUnions;
use app\modules\transporter\models\TblTransporter;
use app\modules\tankermovement\models\TblConfigTxnResult;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_vehicle_qa_inspection".
 *
 * @property integer $vehicle_qa_inspection_code
 * @property string $union_code
 * @property string $transporter_code
 * @property string $vehicle_code
 * @property string $trip_code
 * @property string $transaction_datetime
 * @property string $status
 * @property string $remarks
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblVehicleQaInspection extends \app\models\ChildModel {

    public $config_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_qa_inspection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['config_code', 'transaction_datetime', 'created_at', 'updated_at', 'transporter_code', 'vehicle_code', 'trip_code', 'status', 'remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_by', 'originating_type', 'union_code', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['union_code', 'transporter_code', 'vehicle_code'], 'required'],
                [['vehicle_code'], 'tripCodeRequired', 'except' => 'qaInspection'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vehicle_qa_inspection_code' => Yii::t('app', 'Vehicle Qa Inspection Code'),
            'union_code' => Yii::t('app', 'Union'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'trip_code' => Yii::t('app', 'Trip Code'),
            'transaction_datetime' => Yii::t('app', 'Transaction Datetime'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
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

    public function getTransporter() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getConfigResult() {
        return TblConfigTxnResult::findOne(['ref_code' => $this->vehicle_qa_inspection_code, 'config_code' => $this->config_code, 'config_for' => 'VEHICLE_QA_INSPECTION']);
    }

    public function getVehicleQaInpection($vehicle_code) {
        return $this->find()->where(['vehicle_code' => $vehicle_code, 'status' => 'pending'])->all();
    }

    public function getVehicleList($unionCode) {
        $tankerQualifiedWithin = Yii::$app->general->getUnionConfiguration($unionCode, 'tanker_qualified_within', 'PORTAL');
        $inspectionVehicle = TblVehicleMaster::find()->alias('vm')->select('vm.vehicle_code, vm.parsing_no')
                        ->innerJoin('tbl_vehicle_qa_inspection as vqi', 'vqi.vehicle_code = vm.vehicle_code')
                        ->where(['vqi.status' => 'pending', 'vm.vehicle_use_type' => [1, 2]])
                        ->andWhere(['>=', 'vqi.transaction_datetime', new Expression('DATEADD(HOUR, -' . (int) $tankerQualifiedWithin . ', GETDATE())')])
                        ->orderBy('vqi.transaction_datetime', SORT_DESC)->all();
        return ArrayHelper::map($inspectionVehicle, 'vehicle_code', 'parsing_no');
    }

    public function tripCodeRequired($attribute, $params) {
        $tripModel = new TblVehicleTripDetail();
        $tripData = $tripModel->getOpenTripDetailList($this->union_code, 'qa_inspection', $this->vehicle_code);
        if (!empty($tripData) && empty($this->trip_code)) {
            $this->addError('trip_code', Yii::t('app/validation', $this->getAttributeLabel('trip_code') . ' cannot be blank.'));
            return false;
        }
    }

}
