<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_customer_master_provisional_history".
 *
 * @property integer $id
 * @property integer $customer_provisional_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $customer_code
 * @property string $customer_code_ex
 * @property string $customer_name
 * @property string $customer_type
 * @property string $sap_code
 * @property string $refference_code
 * @property string $address
 * @property string $local_name
 * @property string $local_address
 * @property string $gst_no
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $morning_kms
 * @property string $evening_kms
 * @property string $rate_chart_code
 * @property string $billing_payment_cycle
 * @property string $over_head
 * @property string $ccenter_code
 * @property string $ref_code
 * @property string $old_bmc_code
 * @property string $old_mcc_plant_code
 * @property string $old_route_code
 * @property integer $auto_code
 * @property string $vendor_code
 * @property string $data_post_id
 * @property integer $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $response_datetime
 * @property string $aadhaar_no
 * @property string $ts_code_m
 * @property string $ts_code_e
 * @property string $sap_vendor_code
 * @property string $customer_category
 * @property integer $animal_type_code
 * @property string $distance_from_mcc
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $beneficiary_name
 * @property string $contact_person
 * @property string $email
 * @property string $mobile_no
 * @property string $local_contact_person
 * @property string $department
 * @property string $firstname
 * @property string $lastname
 * @property string $surname
 * @property string $local_firstname
 * @property string $local_lastname
 * @property string $local_surname
 * @property string $status
 * @property string $remarks
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblCustomerMasterProvisionalHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_customer_master_provisional_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['morning_kms', 'union_code', 'state_code', 'ref_code', 'bank_code', 'branch_code', 'remarks', 'originating_org_code', 'sap_vendor_code', 'vendor_code', 'created_by', 'updated_by', 'history_created_by', 'data_post_id', 'ts_code_m', 'ts_code_e', 'beneficiary_name', 'contact_person', 'department', 'firstname', 'lastname', 'surname', 'originating_org_type', 'hamlet_code', 'sub_district_code', 'plant_code', 'local_name', 'local_contact_person', 'local_firstname', 'local_lastname', 'local_surname', 'customer_name', 'customer_type', 'sap_code', 'refference_code', 'customer_category', 'distance_from_mcc', 'status', 'email', 'route_code', 'old_route_code', 'operation_type', 'bmc_code', 'address', 'local_address', 'gst_no', 'rate_chart_code', 'billing_payment_cycle', 'over_head', 'ccenter_code', 'resp_status', 'resp_desc', 'aadhaar_no', 'bank_account_no', 'ifsc', 'mobile_no', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'customer_code', 'customer_code_ex', 'old_bmc_code', 'mcc_plant_code', 'village_code', 'old_mcc_plant_code', 'district_code', 'picked_datetime', 'response_datetime', 'created_at', 'updated_at', 'history_created_at', 'evening_kms', 'customer_provisional_code', 'auto_code', 'data_post_status', 'animal_type_code', 'originating_type', 'response_msg', 'is_sap_approved'], 'safe'],
                [['latitude', 'longitude', 'gender_code', 'pincode', 'pan_no', 'customer_status', 'supervisor_employee_id', 'supervisor_employee_name', 'is_aadhar_verify', 'is_bank_verify', 'provisional_from', 'is_approved', 'approved_at', 'approved_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_provisional_code' => Yii::t('app', 'Customer Provisional Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_code_ex' => Yii::t('app', 'Customer Code Ex'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'sap_code' => Yii::t('app', 'Sap Code'),
            'refference_code' => Yii::t('app', 'Refference Code'),
            'address' => Yii::t('app', 'Address'),
            'local_name' => Yii::t('app', 'Local Name'),
            'local_address' => Yii::t('app', 'Local Address'),
            'gst_no' => Yii::t('app', 'Gst No'),
            'state_code' => Yii::t('app', 'State Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'village_code' => Yii::t('app', 'Village Code'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            'morning_kms' => Yii::t('app', 'Morning Kms'),
            'evening_kms' => Yii::t('app', 'Evening Kms'),
            'rate_chart_code' => Yii::t('app', 'Rate Chart Code'),
            'billing_payment_cycle' => Yii::t('app', 'Billing Payment Cycle'),
            'over_head' => Yii::t('app', 'Over Head'),
            'ccenter_code' => Yii::t('app', 'Ccenter Code'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'old_bmc_code' => Yii::t('app', 'Old Bmc Code'),
            'old_mcc_plant_code' => Yii::t('app', 'Old Mcc Plant Code'),
            'old_route_code' => Yii::t('app', 'Old Route Code'),
            'auto_code' => Yii::t('app', 'Auto Code'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'data_post_id' => Yii::t('app', 'Data Post ID'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'aadhaar_no' => Yii::t('app', 'Aadhaar No'),
            'ts_code_m' => Yii::t('app', 'Ts Code M'),
            'ts_code_e' => Yii::t('app', 'Ts Code E'),
            'sap_vendor_code' => Yii::t('app', 'Sap Vendor Code'),
            'customer_category' => Yii::t('app', 'Customer Category'),
            'animal_type_code' => Yii::t('app', 'Animal Type Code'),
            'distance_from_mcc' => Yii::t('app', 'Distance From Mcc'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'email' => Yii::t('app', 'Email'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'local_contact_person' => Yii::t('app', 'Local Contact Person'),
            'department' => Yii::t('app', 'Department'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'surname' => Yii::t('app', 'Surname'),
            'local_firstname' => Yii::t('app', 'Local Firstname'),
            'local_lastname' => Yii::t('app', 'Local Lastname'),
            'local_surname' => Yii::t('app', 'Local Surname'),
            'status' => Yii::t('app', 'Status'),
            'remarks' => Yii::t('app', 'Remarks'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
