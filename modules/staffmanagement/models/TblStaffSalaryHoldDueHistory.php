<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_salary_hold_due_history".
 *
 * @property integer $id
 * @property integer $staff_salary_hold_due_code
 * @property string $staff_member_code
 * @property string $hold_amount
 * @property string $due_amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblStaffSalaryHoldDueHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_salary_hold_due_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_salary_hold_due_code', 'originating_type'], 'integer'],
            [['staff_member_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'history_created_by', 'operation_type'], 'string'],
            [['hold_amount', 'due_amount'], 'number'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'staff_salary_hold_due_code' => Yii::t('app', 'Staff Salary Hold Due Code'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'hold_amount' => Yii::t('app', 'Hold Amount'),
            'due_amount' => Yii::t('app', 'Due Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
