<?php

namespace app\modules\complaint\models;

use Yii;
use app\modules\complaint\models\TblComplainType;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcs;
use app\modules\assetmanagement\models\TblAssetMaster;
use app\modules\details\models\TblContactDetails;
use webvimark\modules\UserManagement\models\User;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\document\models\TblAttachment;
use app\modules\complaint\models\TblComplainProblem;

/**
 * This is the model class for table "tbl_complain".
 *
 * @property integer $complain_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $location_type
 * @property string $complain_for
 * @property integer $complain_type_code
 * @property string $complain_datetime
 * @property string $complain_assignment_datetime
 * @property string $asset_code
 * @property integer $complain_problem_code
 * @property string $serial_number
 * @property string $new_serial_no
 * @property string $contact_person
 * @property string $mobile_no
 * @property string $complain_status
 * @property string $complain_status_datetime
 * @property string $user_code
 * @property integer $physical_damage
 * @property integer $spare_required
 * @property integer $affects_data
 * @property string $lat_long
 * @property string $location_details
 * @property string $remarks
 * @property string $entry_type
 * @property string $resolved_status
 * @property string $resolved_datetime
 * @property string $resolved_remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblComplain extends \app\models\ChildModel {

    public $activityStatus;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['user_code', 'mobile_no', 'location_details', 'complain_type_code', 'remarks', 'resolved_remarks', 'complain_problem_code', 'physical_damage', 'spare_required', 'affects_data', 'originating_type', 'union_code', 'plant_code', 'mcc_plant_code', 'location_type', 'bmc_code', 'dcs_code', 'complain_for', 'serial_number', 'new_serial_no', 'asset_code', 'contact_person', 'lat_long', 'complain_datetime', 'complain_assignment_datetime', 'complain_status_datetime', 'resolved_datetime', 'created_at', 'updated_at', 'complain_status', 'entry_type', 'resolved_status', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'activityStatus', 'collection_request_type', 'from_date', 'from_shift'], 'safe'],
                [['complain_type_code', 'location_type', 'contact_person', 'mobile_no', 'complain_problem_code'], 'required', 'on' => ['portal_create_complaint', 'portal_update_complaint']],
                [['physical_damage'], 'default', 'value' => 0],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
                [['user_code'], 'required', 'on' => ['assign_complain']],
                [['complain_problem_code', 'resolved_status'], 'required', 'on' => ['portal_resolve_complaint']],
                [['plant_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return ($model->location_type == '1' || $model->location_type == '2' || $model->location_type == '3');
                }, 'whenClient' => "function (attribute, value) { 
                      return ($('#tblcomplain-location_type').val() == '1' || $('#tblcomplain-location_type').val() == '2' || $('#tblcomplain-location_type').val() == '3');
                  }", 'on' => ['portal_create_complaint', 'portal_update_complaint']],
                [['mcc_plant_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return ($model->location_type == '2' || $model->location_type == '3');
                }, 'whenClient' => "function (attribute, value) { 
                      return ($('#tblcomplain-location_type').val() == '2' || $('#tblcomplain-location_type').val() == '3');
                  }", 'on' => ['portal_create_complaint', 'portal_update_complaint']],
                [['bmc_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return ($model->location_type == '2' || $model->location_type == '3');
                }, 'whenClient' => "function (attribute, value) { 
                      return ($('#tblcomplain-location_type').val() == '2' || $('#tblcomplain-location_type').val() == '3');
                  }", 'on' => ['portal_create_complaint', 'portal_update_complaint']],
                [['dcs_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return ($model->location_type == '3');
                }, 'whenClient' => "function (attribute, value) { 
                      return ($('#tblcomplain-location_type').val() == '3');
                  }", 'on' => ['portal_create_complaint', 'portal_update_complaint']],
                [['serial_number'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return ($model->complain_for == 'asset_complain');
                }, 'whenClient' => "function (attribute, value) { 
                      return ($('#tblcomplain-complain_for').val() == 'asset_complain');
                    }", 'on' => ['portal_create_complaint', 'portal_update_complaint']],
                [['asset_code'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return ($model->complain_for == 'asset_complain');
                }, 'whenClient' => "function (attribute, value) { 
                      return ($('#tblcomplain-complain_for').val() == 'asset_complain');
                  }", 'on' => ['portal_create_complaint', 'portal_update_complaint']],
                [['new_serial_no'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return ($model->resolved_status == 'replace' && $model->spare_required == 0 && $model->complain_for == 'asset_complain');
                }, 'whenClient' => "function (attribute, value) { 
                      return ($('#tblcomplain-complain_for').val() == 'asset_complain');
                }", 'on' => ['portal_create_complaint', 'portal_resolve_complaint']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'complain_code' => Yii::t('app', 'Complain Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'location_type' => Yii::t('app', 'Location Type'),
            'complain_for' => Yii::t('app', 'Complain For'),
            'complain_type_code' => Yii::t('app', 'Complain Type'),
            'complain_datetime' => Yii::t('app', 'Complain Datetime'),
            'complain_assignment_datetime' => Yii::t('app', 'Complain Assignment Datetime'),
            'asset_code' => Yii::t('app', 'Asset'),
            'complain_problem_code' => Yii::t('app', 'Complain Problem'),
            'serial_number' => Yii::t('app', 'Serial Number'),
            'new_serial_no' => Yii::t('app', 'New Serial No'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'complain_status' => Yii::t('app', 'Complain Status'),
            'complain_status_datetime' => Yii::t('app', 'Complain Status Datetime'),
            'user_code' => Yii::t('app', 'User'),
            'physical_damage' => Yii::t('app', 'Physical Damage'),
            'spare_required' => Yii::t('app', 'Spare Required'),
            'affects_data' => Yii::t('app', 'Affects Data'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'location_details' => Yii::t('app', 'Location Details'),
            'remarks' => Yii::t('app', 'Remarks'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'resolved_status' => Yii::t('app', 'Resolve Status'),
            'resolved_datetime' => Yii::t('app', 'Resolved Datetime'),
            'resolved_remarks' => Yii::t('app', 'Resolve Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
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

    public function getComplainFors() {
        return $this->hasOne(TblComplainType::className(), ['complain_type_code' => 'complain_type_code']);
    }

    public function getAsset() {
        return $this->hasOne(TblAssetMaster::className(), ['asset_code' => 'asset_code']);
    }

    public function getComplainProblem() {
        return $this->hasOne(TblComplainProblem::className(), ['complain_problem_code' => 'complain_problem_code']);
    }

    public function getComplainFor($complain_type) {
        $query = TblComplain::find()->select(['tbl_complain.complain_for'])
                ->innerJoin('tbl_complain_type', 'tbl_complain_type.complain_type_code = tbl_complain.complain_type_code')
                ->where(['tbl_complain.complain_code' => $complain_type]);

        return $query->all();
    }

    public function checkEditable() {
        return $this->complain_status == 'CREATED' ? TRUE : FALSE;
    }

    public function checkAssign() {
        return $this->complain_status == 'RESOLVED' ? TRUE : FALSE;
    }

    public function getComplainStatus($id) {
        return $this->find()->select('complain_status')->where(['complain_status' => 'RESOLVED', 'complain_code' => $id])->one();
    }

    public function getContactDetailsCode() {
        return $this->hasOne(TblContactDetails::className(), ['detail_code' => 'user_code']);
    }

    public function getContactDetailsCodes() {
        return $this->hasOne(User::className(), ['id' => 'user_code']);
    }

    public function getAttachment() {
        return $this->hasMany(TblAttachment::className(), ['module_code' => 'complain_code']);
    }

}
