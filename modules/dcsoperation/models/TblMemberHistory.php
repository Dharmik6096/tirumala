<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_member_history".
 *
 * @property integer $id
 * @property string $account_no
 * @property string $created_at
 * @property string $created_by
 * @property string $history_created_at
 * @property string $ifsc
 * @property integer $is_active
 * @property string $member_code
 * @property string $member_img
 * @property string $member_name
 * @property string $nominee_name
 * @property string $nominee_relation
 * @property string $operation_type
 * @property integer $payment_mode
 * @property string $pincode
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $animal_type_code
 * @property string $bank_code
 * @property string $branch_code
 * @property integer $caste_category_code
 * @property string $dcs_code
 * @property string $district_code
 * @property string $federation_code
 * @property integer $gender_code
 * @property string $hamlet_code
 * @property integer $member_type_code
 * @property string $state_code
 * @property string $sub_center_code
 * @property string $sub_district_code
 * @property string $union_code
 * @property string $village_code
 * @property string $ex_member_code
 * @property string $father_name
 * @property string $surname
 * @property string $dob
 * @property integer $bloodgroup_code
 * @property integer $qualification_code
 * @property integer $religion_code
 * @property string $land_class
 * @property string $total_land
 * @property integer $no_of_buffalo
 * @property integer $no_of_cow_cross
 * @property integer $no_of_cow_ind
 * @property integer $total_animals
 * @property string $bank_account_no
 * @property string $mobile_no
 * @property string $address
 * @property string $pan_no
 * @property string $adhar_no
 * @property string $voter_id
 * @property integer $annual_income
 * @property string $local_name
 * @property string $local_father_name
 * @property string $local_surname
 * @property string $local_nominee_name
 * @property string $local_address
 * @property integer $data_post_id
 * @property string $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 */
class TblMemberHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['is_active', 'payment_mode', 'animal_type_code', 'caste_category_code', 'gender_code', 'member_type_code', 'bloodgroup_code', 'qualification_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals', 'annual_income', 'account_no', 'created_by', 'ifsc', 'member_code', 'member_img', 'member_name', 'nominee_name', 'operation_type', 'pincode', 'updated_by', 'bank_code', 'branch_code', 'dcs_code', 'district_code', 'federation_code', 'hamlet_code', 'state_code', 'sub_center_code', 'sub_district_code', 'union_code', 'village_code', 'ex_member_code', 'father_name', 'surname', 'dob', 'land_class', 'total_land', 'bank_account_no', 'mobile_no', 'address', 'pan_no', 'adhar_no', 'local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address', 'created_at', 'history_created_at', 'updated_at', 'nominee_relation', 'voter_id', 'religion_code', 'upload', 'is_download', 'download_date_time', 'member_class', 'registration_date', 'ref_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'beneficiary_name', 'is_verified', 'history_created_by', 'witness_name', 'place', 'land', 'land_type', 'farmer_type', 'is_educated', 'is_cooking_gas', 'marital_status', 'registration_no', 'is_milk_machine', 'is_piyet_land', 'is_chaf_cutter', 'is_toilet'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'emilk_sync_status', 'emilk_sync_timestamp'], 'safe'],
                [['ref_code', 'vendor_code', 'auto_code', 'response_datetime', 'received_timestamp', 'bank_remarks', 'contact_remarks', 'aadhaar_card_address', 'is_email_verify', 'email_relation', 'member_identity_no', 'applicant_relation'], 'safe'],
                [['email', 'bank_name', 'branch_name', 'rate_class', 'old_member_code', 'old_dcs_code', 'sap_farmer_code', 'farmer_code', 'sap_local_code', 'is_contact_verified', 'is_dcs_member', 'latitude', 'longitude', 'employee_code', 'employee_name', 'region_code', 'occupation', 'age', 'daily_milk_total', 'home_consumption_milk', 'market_surplus_milk', 'annual_milk_pour', 'application_no', 'is_kyc_verified'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'account_no' => Yii::t('app', 'Account No'),
//            'created_at' => Yii::t('app', 'Created At'),
//            'created_by' => Yii::t('app', 'Created By'),
//            'history_created_at' => Yii::t('app', 'History Created At'),
//            'ifsc' => Yii::t('app', 'Ifsc'),
//            'is_active' => Yii::t('app', 'Is Active'),
//            'member_code' => Yii::t('app', 'Member Code'),
//            'member_img' => Yii::t('app', 'Member Img'),
//            'member_name' => Yii::t('app', 'Member Name'),
//            'nominee_name' => Yii::t('app', 'Nominee Name'),
//            'operation_type' => Yii::t('app', 'Operation Type'),
//            'payment_mode' => Yii::t('app', 'Payment Mode'),
//            'pincode' => Yii::t('app', 'Pincode'),
//            'updated_at' => Yii::t('app', 'Updated At'),
//            'updated_by' => Yii::t('app', 'Updated By'),
//            'animal_type_code' => Yii::t('app', 'Animal Type Code'),
//            'bank_code' => Yii::t('app', 'Bank Code'),
//            'branch_code' => Yii::t('app', 'Branch Code'),
//            'caste_category_code' => Yii::t('app', 'Caste Category Code'),
//            'dcs_code' => Yii::t('app', 'Dcs Code'),
//            'district_code' => Yii::t('app', 'District Code'),
//            'federation_code' => Yii::t('app', 'Federation Code'),
//            'gender_code' => Yii::t('app', 'Gender Code'),
//            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
//            'member_type_code' => Yii::t('app', 'Member Type Code'),
//            'state_code' => Yii::t('app', 'State Code'),
//            'sub_center_code' => Yii::t('app', 'Sub Center Code'),
//            'sub_district_code' => Yii::t('app', 'Sub District Code'),
//            'union_code' => Yii::t('app', 'Union Code'),
//            'village_code' => Yii::t('app', 'Village Code'),
//            'ex_member_code' => Yii::t('app', 'Ex Member Code'),
//            'father_name' => Yii::t('app', 'Father Name'),
//            'surname' => Yii::t('app', 'Surname'),
//            'dob' => Yii::t('app', 'Dob'),
//            'bloodgroup_code' => Yii::t('app', 'Bloodgroup Code'),
//            'qualification_code' => Yii::t('app', 'Qualification Code'),
//            'land_class' => Yii::t('app', 'Land Class'),
//            'total_land' => Yii::t('app', 'Total Land'),
//            'no_of_buffalo' => Yii::t('app', 'No Of Buffalo'),
//            'no_of_cow_cross' => Yii::t('app', 'No Of Cow Cross'),
//            'no_of_cow_ind' => Yii::t('app', 'No Of Cow Ind'),
//            'total_animals' => Yii::t('app', 'Total Animals'),
//            'bank_account_no' => Yii::t('app', 'Bank Account No'),
//            'mobile_no' => Yii::t('app', 'Mobile No'),
//            'address' => Yii::t('app', 'Address'),
//            'pan_no' => Yii::t('app', 'Pan No'),
//            'adhar_no' => Yii::t('app', 'Adhar No'),
//            'annual_income' => Yii::t('app', 'Annual Income'),
//            'local_name' => Yii::t('app', 'Local Name'),
//            'local_father_name' => Yii::t('app', 'Local Father Name'),
//            'local_surname' => Yii::t('app', 'Local Surname'),
//            'local_nominee_name' => Yii::t('app', 'Local Nominee Name'),
//            'local_address' => Yii::t('app', 'Local Address'),
        ];
    }

}
