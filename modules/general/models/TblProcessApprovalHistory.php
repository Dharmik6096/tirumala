<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_process_approval_history".
 *
 * @property integer $id
 * @property integer $process_approval_code
 * @property string $process_code
 * @property string $process_name
 * @property string $approval_mode
 * @property integer $level
 * @property string $level_priority
 * @property integer $status
 * @property string $login_type
 * @property string $user_code
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
class TblProcessApprovalHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_process_approval_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['process_approval_code', 'level', 'status', 'process_code', 'process_name', 'approval_mode', 'level_priority', 'login_type', 'user_code', 'master_approval_mode', 'remarks', 'department'], 'safe'],
                [['created_at', 'updated_at', 'history_created_at', 'created_by', 'updated_by', 'history_created_by', 'operation_type', 'originating_type', 'originating_org_code', 'originating_org_type', 'status_date', 'status_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'process_approval_code' => Yii::t('app', 'Process Approval Code'),
            'process_code' => Yii::t('app', 'Process Code'),
            'process_name' => Yii::t('app', 'Process Name'),
            'approval_mode' => Yii::t('app', 'Approval Mode'),
            'level' => Yii::t('app', 'Level'),
            'level_priority' => Yii::t('app', 'Level Priority'),
            'status' => Yii::t('app', 'Status'),
            'login_type' => Yii::t('app', 'Login Type'),
            'user_code' => Yii::t('app', 'User Code'),
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
            'department' => Yii::t('app', 'Department'),
        ];
    }

}
