<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\transporter\models\TblTransporter;
use app\modules\organisation\models\TblVehicleMaster;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_vehicle_extra_km_daywise".
 *
 * @property integer $extra_km_code
 * @property string $vehicle_code
 * @property string $transporter_code
 * @property string $date
 * @property string $extra_kms
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblVehicleExtraKmDaywise extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_extra_km_daywise';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vehicle_code', 'transporter_code', 'created_by', 'updated_by', 'union_code', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['extra_kms'], 'number', 'min' => 0],
            [['originating_type'], 'integer'],
            [['vehicle_code', 'date', 'extra_kms'], 'required'],
            [['transporter_code'], 'required', 'except' => ['importCsv']],
            [['union_code'], 'required', 'except' => ['importCsv']],
            [['date'], 'convertDateDot', 'on' => ['importCsv']],
            [['date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['date'], 'convertDate', 'on' => ['importCsv']],
            [['vehicle_code'], 'importFieldSet', 'on' => ['importCsv']],
            [['vehicle_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVehicleMaster::className(), 'targetAttribute' => ['vehicle_code' => 'vehicle_code'], 'on' => ['importCsv']],
            [['date'], 'unique', 'targetAttribute' => ['date', 'vehicle_code', 'transporter_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'extra_km_code' => Yii::t('app', 'Extra Km Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'date' => Yii::t('app', 'Date'),
            'extra_kms' => Yii::t('app', 'Extra Kms'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getTransporterCode() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getVehicle() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function importFieldSet($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->transporter_code = Yii::$app->general->getforeignkey($this->vehicle, 'transporter_code');
            $this->union_code = Yii::$app->general->getforeignkey($this->transporterCode, 'union_code');
        }
    }

    public function convertDateDot() {
        try {
            $this->date = Yii::$app->controls->view_date($this->date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->date = !empty($this->date) ? Yii::$app->controls->view_date($this->date, 'php:Y-m-d') : NULL;
        }
    }

}
