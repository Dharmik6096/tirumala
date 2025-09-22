<?php

namespace app\modules\complaint\models;

use Yii;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_software_complaint_txn".
 *
 * @property string $complaint_txn_code
 * @property string $complaint_code
 * @property string $assign_to
 * @property string $assign_date
 * @property string $assign_time
 * @property string $assign_remarks
 * @property string $attachment
 * @property string $complaint_status
 * @property string $resolve_remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblSoftwareComplaintTxn extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_software_complaint_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['assign_time', 'assign_to', 'assign_date', 'assign_date', 'created_at', 'updated_at', 'assign_remarks', 'attachment', 'resolve_remarks', 'originating_type', 'complaint_txn_code', 'complaint_code', 'assign_time', 'complaint_status', 'assign_to', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['assign_time', 'assign_to', 'assign_date'], 'required', 'on' => ['assign']],
                [['assign_to'], 'validateUser', 'on' => ['assign']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'complaint_txn_code' => Yii::t('app', 'Complaint Txn Code'),
            'complaint_code' => Yii::t('app', 'Complaint Code'),
            'assign_to' => Yii::t('app', 'Assign To'),
            'assign_date' => Yii::t('app', 'Assign Date'),
            'assign_time' => Yii::t('app', 'Assign Time'),
            'assign_remarks' => Yii::t('app', 'Assign Remarks'),
            'attachment' => Yii::t('app', 'Attachment'),
            'complaint_status' => Yii::t('app', 'Complaint Status'),
            'resolve_remarks' => Yii::t('app', 'Resolve Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'assign_to']);
    }

    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function validateUser($attribute, $params) {
        $user = \Yii::$app->session->get('UserCode');
        $engineer = \Yii::$app->session->get('isEngineer');
        if ($user == $this->assign_to && $engineer != 1) {
            $this->addError($attribute, Yii::t('app/validation', 'Not Allow to Self Assign'));
        }
    }

}
