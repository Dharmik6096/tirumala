<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\globalmaster\models\TblDesignation;

/**
 * This is the model class for table "tbl_staff_member_designation".
 *
 * @property string $staff_member_designation_code
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $remark
 * @property string $tenure_from_date
 * @property string $tenure_to_date
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $designation_code
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
 */
class TblStaffMemberDesignation extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_member_designation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_member_designation_code', 'staff_member_code', 'designation_code', 'tenure_from_date'], 'required'],
            [['staff_member_designation_code', 'created_by', 'remark', 'updated_by', 'staff_member_code', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'union_code'], 'string'],
            [['created_at', 'tenure_from_date', 'tenure_to_date', 'updated_at', 'union_code'], 'safe'],
            [['is_active', 'designation_code', 'originating_type'], 'integer'],
            [['staff_member_designation_code', 'staff_member_code'], 'string', 'max' => 20],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['remark'], 'string', 'max' => 100],
            [['is_active'], 'default', 'value' => 1],
            [['tenure_from_date'], 'validatePreDate'],
            [['tenure_from_date'], 'validatePreToDate'],
            [['tenure_to_date'], 'validateToDate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staff_member_designation_code' => Yii::t('app', 'Staff Member Designation Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'remark' => Yii::t('app', 'Remark'),
            'tenure_from_date' => Yii::t('app', 'Start Date'),
            'tenure_to_date' => Yii::t('app', 'End Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'designation_code' => Yii::t('app', 'Designation'),
            'staff_member_code' => Yii::t('app', 'Staff Member'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    public function getExistData($id) {
        return $this->find()
                        ->where(['staff_member_code' => $id])
                        ->all();
    }

    public function disableEdit() {
        $count = $this->find()
                ->where(['staff_member_code' => $this->staff_member_code])
                ->andWhere(['!=', 'staff_member_designation_code', $this->staff_member_designation_code])
                ->andWhere(['>=', 'tenure_from_date', $this->tenure_from_date])
                ->count();
        if ($count > 0) {
            return true;
        }
        return false;
    }

    public function validatePreDate($attribute, $params) {
        $disable = $this->disableEdit();
        if ($disable == true) {
            $this->addError($attribute, Yii::t('app/validation', 'Start Date must be greater than previous Start Date'));
            return false;
        }
    }

    public function getPreviousData($id) {
        return $this->find()
                        ->select('staff_member_designation_code,tenure_to_date')
                        ->where(['staff_member_code' => $id])
                        ->orderBy('staff_member_designation_code desc')
                        ->one();
    }

    public function validatePreToDate($attribute, $params) {
        $data = $this->find()
                ->where(['staff_member_code' => $this->staff_member_code])
                ->andWhere(['!=', 'staff_member_designation_code', $this->staff_member_designation_code])
                ->andWhere(['>', 'tenure_to_date', $this->tenure_from_date])
                ->andWhere(['is not', 'tenure_to_date', null])
                ->orderBy('tenure_to_date desc')
                ->one();
        if (!empty($data)) {
            $to_date = !empty($data->tenure_to_date) ? date('d-m-Y', strtotime($data->tenure_to_date)) : NULL;
            $this->addError($attribute, Yii::t('app/validation', 'From Date must be after ' . $to_date));
            return false;
        }
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->tenure_to_date) && !empty($this->tenure_from_date) && ($this->tenure_from_date > $this->tenure_to_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'Tenure To Must be Greater than Tenure From'));
            return false;
        }
    }

    public function getStaffMemberCode() {
        return $this->hasOne(TblStaffMember::className(), ['staff_member_code' => 'staff_member_code']);
    }

    public function getDesignationCode() {
        return $this->hasOne(TblDesignation::className(), ['designation_code' => 'designation_code']);
    }

}
