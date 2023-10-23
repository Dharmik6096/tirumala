<?php

namespace app\modules\configuration\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use app\modules\general\models\TblProcessApproval;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_shift_time_exceed".
 *
 * @property string $shift_time_exceed_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $org_type
 * @property string $org_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property string $standard_time
 * @property string $exceed_time
 * @property string $remarks
 * @property string $status
 * @property string $status_datetime
 * @property string $status_by
 * @property string $status_remarks
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
class TblShiftTimeExceed extends \app\models\ChildModel {

    public $process_approval_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_shift_time_exceed';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['shift_time_exceed_code'], 'required'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'org_type', 'date_time_of_collection', 'standard_time', 'exceed_time', 'status_datetime', 'created_at', 'updated_at', 'shift_code', 'originating_type', 'status', 'created_by', 'updated_by', 'shift_time_exceed_code', 'org_code', 'remarks', 'status_remarks', 'status_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'process_approval_code'], 'safe'],
                [['shift_time_exceed_code', 'org_type', 'org_code', 'date_time_of_collection', 'standard_time', 'exceed_time', 'shift_code'], 'required', 'on' => ['create_shift_time']],
                [['plant_code', 'mcc_plant_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return ($model->org_type == 'MCC' || $model->org_type == 'BMC' || $model->org_type == 'VLC');
                }, 'whenClient' => "function (attribute, value) { 
                      return ($('#tblshifttimeexceed-org_type').val() == 'MCC' || $('#tblshifttimeexceed-org_type').val() == 'BMC' || $('#tblshifttimeexceed-org_type').val() == 'VLC');
                }", 'on' => ['create_shift_time']],
                [['bmc_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return ($model->org_type == 'BMC' || $model->org_type == 'VLC');
                }, 'whenClient' => "function (attribute, value) { 
                      return ($('#tblshifttimeexceed-org_type').val() == 'BMC' || $('#tblshifttimeexceed-org_type').val() == 'VLC');
                }", 'on' => ['create_shift_time']],
                [['dcs_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return ($model->org_type == 'VLC');
                }, 'whenClient' => "function (attribute, value) { 
                      return ($('#tblshifttimeexceed-org_type').val() == 'VLC');
                  }", 'on' => ['create_shift_time']],
                [['date_time_of_collection'], 'unique', 'targetAttribute' => ['org_type', 'org_code', 'date_time_of_collection'], 'message' => Yii::t('app/validation', 'Shift Time Exceed has been already taken.'), 'on' => 'create_shift_time'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'shift_time_exceed_code' => Yii::t('app', 'Shift Time Exceed Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'org_type' => Yii::t('app', 'Org Type'),
            'org_code' => Yii::t('app', 'Org Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift'),
            'standard_time' => Yii::t('app', 'Standard Time'),
            'exceed_time' => Yii::t('app', 'Exceed Time'),
            'remarks' => Yii::t('app', 'Remarks'),
            'status' => Yii::t('app', 'Status'),
            'status_datetime' => Yii::t('app', 'Status Datetime'),
            'status_by' => Yii::t('app', 'Status By'),
            'status_remarks' => Yii::t('app', 'Status Remarks'),
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

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
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

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getStandardTimeData($postData) {
        if (!empty($postData)) {
            if ($postData['org_type'] == 'BMC' || $postData['org_type'] == 'MCC') {
                $shiftCode = $postData['shift_code'];

                $data = (new \yii\db\Query)
                        ->select(['m_lock_time', 'e_lock_time'])
                        ->from('tbl_shift_time_android')
                        ->where(['org_type' => $postData['org_type'], 'org_code' => $postData['code']])
                        ->one();

                $standardTime = ($shiftCode == '1') ? $data['m_lock_time'] : $data['e_lock_time'];

                return ['standard_time' => $standardTime];
            } else if ($postData['org_type'] == 'VLC') {
                $shiftCode = $postData['shift_code'];
                $data = (new \yii\db\Query)
                        ->select(['m_lock_time', 'e_lock_time'])
                        ->from('tbl_dpu_incentive_master')
                        ->where(['dcs_code' => $postData['code']])
                        ->one();

                $standardTime = ($shiftCode == '1') ? $data['m_lock_time'] : $data['e_lock_time'];

                return ['standard_time' => $standardTime];
            }
        }

        return ['standard_time' => null];
    }

    public function getShiftTimeExceedApproval() {
        return $this->hasMany(TblProcessApproval::className(), ['process_code' => 'shift_time_exceed_code'])->orderBy('level ASC');
    }

}
