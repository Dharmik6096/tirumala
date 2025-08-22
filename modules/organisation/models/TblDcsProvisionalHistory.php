<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_provisional_history".
 *
 * @property integer $id
 * @property integer $dcs_provisional_code
 * @property string $dcs_code
 * @property string $address
 * @property integer $allow_multi_family_member
 * @property string $bank_account_no
 * @property string $contact_person
 * @property string $dcs_code_ex
 * @property string $dcs_name
 * @property string $dcs_short_name
 * @property string $destination_code
 * @property integer $destination_type
 * @property string $effective_date
 * @property string $email
 * @property string $ifsc
 * @property integer $is_active
 * @property integer $is_bmc
 * @property string $mobile_no
 * @property string $pan_no
 * @property string $phone_no
 * @property string $pincode
 * @property string $registration_code
 * @property string $registration_date
 * @property string $service_tax
 * @property string $tin_no
 * @property string $upi_no
 * @property string $bank_code
 * @property string $branch_code
 * @property string $beneficiary_name
 * @property integer $dcs_type_code
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $route_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $union_code
 * @property string $village_code
 * @property string $block_code
 * @property string $local_name
 * @property string $local_address
 * @property string $local_short_name
 * @property string $local_contact_person
 * @property string $logo_path
 * @property string $punch_line
 * @property integer $mapped_village_no
 * @property string $secretory_info
 * @property string $gst_no
 * @property string $fssi
 * @property integer $organisation_type_code
 * @property integer $scheme_type_code
 * @property integer $is_registered
 * @property string $valid_from
 * @property integer $data_post_status
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $DPUVersionNo
 * @property integer $rate_flag
 * @property string $bank_name
 * @property string $branch_name
 * @property integer $is_name_request
 * @property string $department
 * @property string $firstname
 * @property string $lastname
 * @property string $surname
 * @property string $local_firstname
 * @property string $local_lastname
 * @property string $local_surname
 * @property integer $is_dispatch_mandate
 * @property integer $is_weight_manual
 * @property integer $is_quality_manual
 * @property integer $dpu_type
 * @property integer $member_rate_code
 * @property string $old_bmc_code
 * @property string $old_mcc_plant_code
 * @property string $old_route_code
 * @property integer $is_live
 * @property integer $is_single_farmer
 * @property string $morning_kms
 * @property string $evening_kms
 * @property string $ccenter_code
 * @property string $center_code
 * @property string $vendor_code
 * @property string $sap_center_code
 * @property string $rate_chart_code
 * @property string $ref_code
 * @property integer $default_milk_type
 * @property integer $credit_sale_allow
 * @property integer $auto_code
 * @property integer $mfile_digit
 * @property string $data_post_id
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $response_datetime
 * @property string $aadhaar_no
 * @property integer $is_chiller
 * @property string $ts_code_m
 * @property string $ts_code_e
 * @property string $sap_vendor_code
 * @property string $password
 * @property integer $antibiotic_check
 * @property string $cutoff
 * @property string $lower_milk_type
 * @property string $milk_type
 * @property string $cutoff_val
 * @property string $gender
 * @property string $voter_id
 * @property string $dob
 * @property string $account_type
 * @property integer $is_security_cheque
 * @property string $cheque_number
 * @property string $cheque_amount
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
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblDcsProvisionalHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_provisional_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dcs_provisional_code', 'allow_multi_family_member', 'destination_type', 'is_active', 'is_bmc', 'dcs_type_code', 'mapped_village_no', 'organisation_type_code', 'scheme_type_code', 'is_registered', 'data_post_status', 'rate_flag', 'is_name_request', 'is_dispatch_mandate', 'is_weight_manual', 'is_quality_manual', 'dpu_type', 'member_rate_code', 'is_live', 'is_single_farmer', 'default_milk_type', 'credit_sale_allow', 'auto_code', 'mfile_digit', 'is_chiller', 'antibiotic_check', 'is_security_cheque', 'originating_type', 'remarks'], 'safe'],
                [['effective_date', 'registration_date', 'valid_from', 'picked_datetime', 'response_datetime', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['DPUVersionNo'], 'safe'],
                [['morning_kms', 'evening_kms', 'cheque_amount'], 'safe'],
                [['dcs_code', 'bmc_code', 'old_bmc_code'], 'safe'],
                [['address', 'dcs_name'], 'safe'],
                [['bank_account_no', 'ifsc', 'mobile_no', 'pan_no', 'phone_no', 'upi_no', 'ccenter_code', 'center_code', 'vendor_code', 'sap_center_code', 'rate_chart_code', 'resp_status', 'resp_desc', 'aadhaar_no', 'ts_code_m', 'ts_code_e', 'milk_type', 'dob', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['contact_person', 'dcs_short_name', 'beneficiary_name', 'punch_line', 'department', 'firstname', 'lastname', 'surname', 'password', 'gender', 'account_type', 'cheque_number', 'security_return_date', 'security_return_amt', 'security_return_mode', 'cheque_bank'], 'safe'],
                [['dcs_code_ex', 'route_code', 'old_route_code', 'cutoff', 'lower_milk_type', 'cutoff_val', 'operation_type'], 'safe'],
                [['destination_code', 'branch_code'], 'safe'],
                [['email'], 'safe'],
                [['pincode', 'village_code', 'mcc_plant_code', 'plant_code', 'old_mcc_plant_code'], 'safe'],
                [['registration_code', 'service_tax', 'tin_no', 'gst_no', 'fssi', 'fssi_expiry_date'], 'safe'],
                [['bank_code'], 'safe'],
                [['district_code', 'union_code'], 'safe'],
                [['hamlet_code'], 'safe'],
                [['state_code'], 'safe'],
                [['sub_district_code'], 'safe'],
                [['block_code'], 'safe'],
                [['local_name', 'local_short_name', 'local_contact_person', 'logo_path', 'secretory_info', 'bank_name', 'branch_name'], 'safe'],
                [['local_address'], 'safe'],
                [['local_firstname', 'local_lastname', 'local_surname'], 'safe'],
                [['ref_code', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['data_post_id', 'status'], 'safe'],
                [['sap_vendor_code'], 'safe'],
                [['voter_id'], 'safe'],
                [['created_by', 'updated_by', 'history_created_by'], 'safe'],
                [['latitude', 'longitude', 'dcs_status', 'supervisor_employee_id', 'supervisor_employee_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'dcs_provisional_code' => Yii::t('app', 'Dcs Provisional Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'address' => Yii::t('app', 'Address'),
            'allow_multi_family_member' => Yii::t('app', 'Allow Multi Family Member'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'dcs_code_ex' => Yii::t('app', 'Dcs Code Ex'),
            'dcs_name' => Yii::t('app', 'Dcs Name'),
            'dcs_short_name' => Yii::t('app', 'Dcs Short Name'),
            'destination_code' => Yii::t('app', 'Destination Code'),
            'destination_type' => Yii::t('app', 'Destination Type'),
            'effective_date' => Yii::t('app', 'Effective Date'),
            'email' => Yii::t('app', 'Email'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_bmc' => Yii::t('app', 'Is Bmc'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'pincode' => Yii::t('app', 'Pincode'),
            'registration_code' => Yii::t('app', 'Registration Code'),
            'registration_date' => Yii::t('app', 'Registration Date'),
            'service_tax' => Yii::t('app', 'Service Tax'),
            'tin_no' => Yii::t('app', 'Tin No'),
            'upi_no' => Yii::t('app', 'Upi No'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'dcs_type_code' => Yii::t('app', 'Dcs Type Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'state_code' => Yii::t('app', 'State Code'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'village_code' => Yii::t('app', 'Village Code'),
            'block_code' => Yii::t('app', 'Block Code'),
            'local_name' => Yii::t('app', 'Local Name'),
            'local_address' => Yii::t('app', 'Local Address'),
            'local_short_name' => Yii::t('app', 'Local Short Name'),
            'local_contact_person' => Yii::t('app', 'Local Contact Person'),
            'logo_path' => Yii::t('app', 'Logo Path'),
            'punch_line' => Yii::t('app', 'Punch Line'),
            'mapped_village_no' => Yii::t('app', 'Mapped Village No'),
            'secretory_info' => Yii::t('app', 'Secretory Info'),
            'gst_no' => Yii::t('app', 'Gst No'),
            'fssi' => Yii::t('app', 'FSSAI'),
            'fssi_expiry_date' => Yii::t('app', 'FSSAI Expiry Date'),
            'organisation_type_code' => Yii::t('app', 'Organisation Type Code'),
            'scheme_type_code' => Yii::t('app', 'Scheme Type Code'),
            'is_registered' => Yii::t('app', 'Is Registered'),
            'valid_from' => Yii::t('app', 'Valid From'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'DPUVersionNo' => Yii::t('app', 'Dpu Version No'),
            'rate_flag' => Yii::t('app', 'Rate Flag'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'is_name_request' => Yii::t('app', 'Is Name Request'),
            'department' => Yii::t('app', 'Department'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'surname' => Yii::t('app', 'Surname'),
            'local_firstname' => Yii::t('app', 'Local Firstname'),
            'local_lastname' => Yii::t('app', 'Local Lastname'),
            'local_surname' => Yii::t('app', 'Local Surname'),
            'is_dispatch_mandate' => Yii::t('app', 'Is Dispatch Mandate'),
            'is_weight_manual' => Yii::t('app', 'Is Weight Manual'),
            'is_quality_manual' => Yii::t('app', 'Is Quality Manual'),
            'dpu_type' => Yii::t('app', 'Dpu Type'),
            'member_rate_code' => Yii::t('app', 'Member Rate Code'),
            'old_bmc_code' => Yii::t('app', 'Old Bmc Code'),
            'old_mcc_plant_code' => Yii::t('app', 'Old Mcc Plant Code'),
            'old_route_code' => Yii::t('app', 'Old Route Code'),
            'is_live' => Yii::t('app', 'Is Live'),
            'is_single_farmer' => Yii::t('app', 'Is Single Farmer'),
            'morning_kms' => Yii::t('app', 'Morning Kms'),
            'evening_kms' => Yii::t('app', 'Evening Kms'),
            'ccenter_code' => Yii::t('app', 'Ccenter Code'),
            'center_code' => Yii::t('app', 'Center Code'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'sap_center_code' => Yii::t('app', 'Sap Center Code'),
            'rate_chart_code' => Yii::t('app', 'Rate Chart Code'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'default_milk_type' => Yii::t('app', 'Default Milk Type'),
            'credit_sale_allow' => Yii::t('app', 'Credit Sale Allow'),
            'auto_code' => Yii::t('app', 'Auto Code'),
            'mfile_digit' => Yii::t('app', 'Mfile Digit'),
            'data_post_id' => Yii::t('app', 'Data Post ID'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'aadhaar_no' => Yii::t('app', 'Aadhaar No'),
            'is_chiller' => Yii::t('app', 'Is Chiller'),
            'ts_code_m' => Yii::t('app', 'Ts Code M'),
            'ts_code_e' => Yii::t('app', 'Ts Code E'),
            'sap_vendor_code' => Yii::t('app', 'Sap Vendor Code'),
            'password' => Yii::t('app', 'Password'),
            'antibiotic_check' => Yii::t('app', 'Antibiotic Check'),
            'cutoff' => Yii::t('app', 'Cutoff'),
            'lower_milk_type' => Yii::t('app', 'Lower Milk Type'),
            'milk_type' => Yii::t('app', 'Milk Type'),
            'cutoff_val' => Yii::t('app', 'Cutoff Val'),
            'gender' => Yii::t('app', 'Gender'),
            'voter_id' => Yii::t('app', 'Voter ID'),
            'dob' => Yii::t('app', 'Dob'),
            'account_type' => Yii::t('app', 'Account Type'),
            'is_security_cheque' => Yii::t('app', 'Is Security Cheque'),
            'cheque_number' => Yii::t('app', 'Cheque Number'),
            'cheque_amount' => Yii::t('app', 'Cheque Amount'),
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
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

}
