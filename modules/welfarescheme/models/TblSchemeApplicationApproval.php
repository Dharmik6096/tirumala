<?php

namespace app\modules\welfarescheme\models;

use Yii;
use app\modules\welfarescheme\models\TblSchemeApplication;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_scheme_application_approval".
 *
 * @property integer $app_approval_id
 * @property integer $application_id
 * @property integer $level
 * @property string $user_code
 * @property string $approval_mode
 * @property string $approved_value
 * @property string $application_status
 * @property string $status_date
 * @property string $status_by
 * @property string $status_remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblSchemeApplicationApproval extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_scheme_application_approval';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['approved_value', 'application_status'], 'required', 'on' => 'approve'],
                [['application_id', 'level', 'originating_type', 'scheme_id'], 'safe'],
                [['approved_value'], 'number'],
                [['status_date', 'created_at', 'updated_at'], 'safe'],
                [['user_code', 'application_status', 'status_by'], 'string', 'max' => 20],
                [['approval_mode'], 'string', 'max' => 10],
                [['status_remarks'], 'string', 'max' => 255],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'app_approval_id' => 'App Approval ID',
            'application_id' => 'Application ID',
            'level' => 'Level',
            'user_code' => 'User Code',
            'approval_mode' => 'Approval Mode',
            'approved_value' => 'Approved Value',
            'application_status' => 'Status',
            'status_date' => 'Status Date',
            'status_by' => 'Status By',
            'status_remarks' => 'Remarks',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
        ];
    }

    public function getSchemeApplication() {
        return $this->hasOne(TblSchemeApplication::className(), ['application_id' => 'application_id']);
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['user_code' => 'user_code']);
    }

    public function getStatusBy() {
        return $this->hasOne(User::className(), ['user_code' => 'status_by']);
    }

}
