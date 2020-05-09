<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_salary_history".
 *
 * @property integer $id
 * @property string $staff_salary_code
 * @property string $addition
 * @property string $created_at
 * @property string $created_by
 * @property string $deduction
 * @property string $updated_at
 * @property string $updated_by
 * @property string $wef_date
 * @property string $staff_member_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $union_code
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblStaffSalaryHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_salary_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_salary_code', 'created_by', 'updated_by', 'staff_member_code', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'union_code', 'operation_type', 'history_created_by'], 'string'],
            [['addition', 'deduction'], 'number'],
            [['created_at', 'updated_at', 'wef_date', 'history_created_at'], 'safe'],
            [['originating_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'staff_salary_code' => Yii::t('app', 'Staff Salary Code'),
            'addition' => Yii::t('app', 'Addition'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deduction' => Yii::t('app', 'Deduction'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'union_code' => Yii::t('app', 'Union Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
