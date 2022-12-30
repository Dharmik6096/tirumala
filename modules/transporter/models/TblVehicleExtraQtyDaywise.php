<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\transporter\models\TblTransporter;
use app\modules\organisation\models\TblVehicleMaster;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_vehicle_extra_qty_daywise".
 *
 * @property integer $extra_qty_code
 * @property string $additional_qty
 * @property string $deduction_qty
 * @property string $rate
 * @property string $date
 * @property string $vehicle_code
 * @property string $transporter_code
 * @property string $union_code
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblVehicleExtraQtyDaywise extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vehicle_extra_qty_daywise';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vehicle_code', 'transporter_code', 'created_by', 'updated_by', 'union_code', 'originating_org_code', 'originating_org_type'], 'string'],
                [['date', 'created_at', 'updated_at', 'rate'], 'safe'],
                [['remarks', 'date', 'created_at', 'updated_at', 'rate', 'vehicle_code', 'transporter_code', 'created_by', 'updated_by', 'union_code', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['additional_qty', 'deduction_qty'], 'number', 'min' => 0],
                [['originating_type'], 'integer'],
                [['vehicle_code', 'date', 'additional_qty', 'deduction_qty'], 'required'],
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
            'extra_qty_code' => Yii::t('app', 'Extra Qty Code'),
            'additional_qty' => Yii::t('app', 'Additional Qty'),
            'deduction_qty' => Yii::t('app', 'Deduction Qty'),
            'rate' => Yii::t('app', 'Rate'),
            'date' => Yii::t('app', 'Date'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
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
