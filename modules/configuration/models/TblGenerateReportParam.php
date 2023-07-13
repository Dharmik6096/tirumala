<?php

namespace app\modules\configuration\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_generate_report_param".
 *
 * @property integer $report_param_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $from_date
 * @property string $to_date
 * @property string $report_key
 * @property string $file_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $data_post_status
 * @property string $resp_status
 * @property string $resp_desc
 */
class TblGenerateReportParam extends \app\models\ChildModel {

    public $report_type, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_generate_report_param';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'plant_code', 'mcc_code', 'dcs_code', 'bmc_code', 'from_date', 'to_date', 'report_key', 'file_name', 'member_code', 'originating_type'], 'safe'],
            [['resp_status', 'resp_desc', 'data_post_status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'ref_code', 'report_name'], 'safe'],
            [['data_post_status'], 'default', 'value' => 0],
            [['report_type', 'from_shift', 'to_shift'], 'safe'],
            [['report_name', 'union_code', 'plant_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['createFront']],
            [['mcc_code'], 'required', 'when' => function ($model) {
                    return $model->report_name == '108 - Milk Collection Data';
                }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblgeneratereportparam-report_name').val() == '108 - Milk Collection Data'; 
                        }", 'on' => ['createFront']],
            [['report_type'], 'required', 'when' => function ($model) {
                    return $model->report_name == '101 - Member Collection Detail';
                }, 'whenClient' => "function (attribute, value) { 
                            return $('#tblgeneratereportparam-report_name').val() == '101 - Member Collection Detail'; 
                        }", 'on' => ['createFront']],
            [['to_date'], function ($attribute, $params) {
                    Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_date', 'to_date', 15, '<', 'Day Difference can be greater than 15.');
                }, 'skipOnEmpty' => false, 'on' => ['createFront']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'report_param_code' => Yii::t('app', 'Report Param Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_code' => Yii::t('app', 'MCC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'member_code' => Yii::t('app', 'Member'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'report_key' => Yii::t('app', 'Report Key'),
            'file_name' => Yii::t('app', 'File Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getPickRecords($limit = 100) {
        $query = $this->find()
                ->where(['data_post_status' => 0, 'ref_code' => NULL]);
        return $query->limit($limit)->all();
    }

    public function updateFileStatus($ids) {
        return $this->updateAll(['data_post_status' => 1], ['report_param_code' => $ids]);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getExistData() {
        return $query = $this->find()->where(['union_code' => $this->union_code, 'plant_code' => $this->plant_code, 'mcc_code' => $this->mcc_code, 'bmc_code' => $this->bmc_code, 'dcs_code' => $this->dcs_code, 'member_code' => $this->member_code, 'from_date' => $this->from_date, 'to_date' => $this->to_date, 'originating_type' => $this->originating_type, 'report_key' => $this->report_key, 'data_post_status' => 0])->one();
    }

    public function updateFileName($ids, $status, $file, $desc) {
        return $this->updateAll(['data_post_status' => $status, 'file_name' => $file, 'resp_desc' => $desc], ['ref_code' => $ids]);
    }

}
