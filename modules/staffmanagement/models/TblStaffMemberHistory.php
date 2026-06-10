<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_member_history".
 *
 * @property integer $id
 * @property string $staff_member_code
 * @property string $staff_member_name
 * @property string $aadhar_card_no
 * @property string $address
 * @property string $bank_account_no
 * @property string $birth_date
 * @property string $created_at
 * @property string $created_by
 * @property string $email_id
 * @property string $ifsc
 * @property integer $is_active
 * @property string $mobile_no
 * @property string $pan_no
 * @property integer $payment_mode
 * @property string $pincode
 * @property string $tenure_from_date
 * @property string $tenure_to_date
 * @property string $updated_at
 * @property string $updated_by
 * @property string $bank_code
 * @property integer $blood_group_code
 * @property string $branch_code
 * @property integer $caste_category_code
 * @property integer $designation_code
 * @property string $district_code
 * @property integer $gender_code
 * @property string $hamlet_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $union_code
 * @property integer $qualification_code
 * @property string $department
 * @property string $ex_staff_member_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblStaffMemberHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_member_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_member_code', 'staff_member_name', 'aadhar_card_no', 'address', 'bank_account_no', 'birth_date', 'created_by', 'email_id', 'ifsc', 'mobile_no', 'pan_no', 'pincode', 'updated_by', 'bank_code', 'branch_code', 'district_code', 'hamlet_code', 'state_code', 'sub_district_code', 'village_code', 'union_code', 'department', 'ex_staff_member_code', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'operation_type', 'history_created_by'], 'safe'],
            [['created_at', 'tenure_from_date', 'tenure_to_date', 'updated_at', 'history_created_at', 'approved_date', 'salary', 'member_code'], 'safe'],
            [['is_active', 'payment_mode', 'blood_group_code', 'caste_category_code', 'designation_code', 'gender_codegender_code', 'qualification_code', 'originating_type', 'aadhar_card_no', 'is_on_role', 'uan_no', 'esic_no', 'pf_no'], 'safe'],
            [['is_committee', 'is_disabled', 'is_trained', 'nominee_name', 'nominee_relation', 'guarantor_name', 'guarantor_mobile', 'pf_loan_amount', 'pf_amount'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'staff_member_name' => Yii::t('app', 'Staff Member Name'),
            'aadhar_card_no' => Yii::t('app', 'Aadhar Card No'),
            'address' => Yii::t('app', 'Address'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'email_id' => Yii::t('app', 'Email ID'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'is_active' => Yii::t('app', 'Is Active'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'payment_mode' => Yii::t('app', 'Payment Mode'),
            'pincode' => Yii::t('app', 'Pincode'),
            'tenure_from_date' => Yii::t('app', 'Tenure From Date'),
            'tenure_to_date' => Yii::t('app', 'Tenure To Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'blood_group_code' => Yii::t('app', 'Blood Group Code'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'caste_category_code' => Yii::t('app', 'Caste Category Code'),
            'designation_code' => Yii::t('app', 'Designation Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'gender_code' => Yii::t('app', 'Gender Code'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            'state_code' => Yii::t('app', 'State Code'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'village_code' => Yii::t('app', 'Village Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'qualification_code' => Yii::t('app', 'Qualification Code'),
            'department' => Yii::t('app', 'Department'),
            'ex_staff_member_code' => Yii::t('app', 'Ex Staff Member Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
