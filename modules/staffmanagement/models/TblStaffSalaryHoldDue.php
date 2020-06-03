<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_salary_hold_due".
 *
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
 */
class TblStaffSalaryHoldDue extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_salary_hold_due';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_member_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
            [['hold_amount', 'due_amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['originating_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
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
        ];
    }

}
