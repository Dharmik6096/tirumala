<?php

namespace app\modules\complaint\models;

use Yii;

/**
 * This is the model class for table "tbl_complain_history".
 *
 * @property integer $id
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblComplainHistory extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_complain_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_code', 'mobile_no', 'location_details', 'remarks', 'resolved_remarks', 'complain_for', 'serial_number', 'contact_person', 'lat_long', 'new_serial_no', 'bmc_code', 'dcs_code', 'asset_code', 'complain_code', 'union_code', 'plant_code', 'mcc_plant_code', 'location_type', 'complain_type_code', 'complain_problem_code', 'physical_damage', 'spare_required', 'affects_data', 'originating_type', 'complain_datetime', 'complain_assignment_datetime', 'complain_status_datetime', 'resolved_datetime', 'created_at', 'updated_at', 'history_created_at', 'complain_status', 'entry_type', 'resolved_status', 'created_by', 'updated_by', 'history_created_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'collection_request_type', 'from_date', 'from_shift'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'complain_code' => Yii::t('app', 'Complain Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'location_type' => Yii::t('app', 'Location Type'),
            'complain_for' => Yii::t('app', 'Complain For'),
            'complain_type_code' => Yii::t('app', 'Complain Type Code'),
            'complain_datetime' => Yii::t('app', 'Complain Datetime'),
            'complain_assignment_datetime' => Yii::t('app', 'Complain Assignment Datetime'),
            'asset_code' => Yii::t('app', 'Asset Code'),
            'complain_problem_code' => Yii::t('app', 'Complain Problem Code'),
            'serial_number' => Yii::t('app', 'Serial Number'),
            'new_serial_no' => Yii::t('app', 'New Serial No'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'complain_status' => Yii::t('app', 'Complain Status'),
            'complain_status_datetime' => Yii::t('app', 'Complain Status Datetime'),
            'user_code' => Yii::t('app', 'User Code'),
            'physical_damage' => Yii::t('app', 'Physical Damage'),
            'spare_required' => Yii::t('app', 'Spare Required'),
            'affects_data' => Yii::t('app', 'Affects Data'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'location_details' => Yii::t('app', 'Location Details'),
            'remarks' => Yii::t('app', 'Remarks'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'resolved_status' => Yii::t('app', 'Resolved Status'),
            'resolved_datetime' => Yii::t('app', 'Resolved Datetime'),
            'resolved_remarks' => Yii::t('app', 'Resolved Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
