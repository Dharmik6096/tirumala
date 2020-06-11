<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_leave_master_history".
 *
 * @property integer $id
 * @property string $staff_leave_code
 * @property string $union_code
 * @property string $leave_type
 * @property integer $leave_for
 * @property integer $is_half
 * @property integer $is_default
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblStaffLeaveMasterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_leave_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_leave_code', 'union_code', 'leave_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'operation_type', 'history_created_by'], 'safe'],
            [['leave_for', 'is_half', 'is_default', 'is_active', 'originating_type'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'staff_leave_code' => Yii::t('app', 'Staff Leave Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'leave_type' => Yii::t('app', 'Leave Type'),
            'leave_for' => Yii::t('app', 'Leave For'),
            'is_half' => Yii::t('app', 'Is Half'),
            'is_default' => Yii::t('app', 'Is Default'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
