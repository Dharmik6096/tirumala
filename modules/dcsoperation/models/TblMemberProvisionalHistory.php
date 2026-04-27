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
class TblMemberProvisionalHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_provisional_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['provisional_member_code', 'is_active', 'payment_mode', 'animal_type_code', 'caste_category_code', 'gender_code', 'member_type_code', 'bloodgroup_code', 'qualification_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals', 'annual_income', 'account_no', 'created_by', 'ifsc', 'member_code', 'member_img', 'member_name', 'nominee_name', 'operation_type', 'pincode', 'updated_by', 'bank_code', 'branch_code', 'dcs_code', 'district_code', 'federation_code', 'hamlet_code', 'state_code', 'sub_center_code', 'sub_district_code', 'union_code', 'village_code', 'ex_member_code', 'father_name', 'surname', 'dob', 'land_class', 'total_land', 'bank_account_no', 'mobile_no', 'address', 'pan_no', 'adhar_no', 'local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address', 'created_at', 'history_created_at', 'updated_at', 'nominee_relation', 'voter_id', 'religion_code', 'upload', 'is_download', 'download_date_time', 'member_class', 'registration_date', 'ref_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'provisional_status', 'remarks', 'bmc_code', 'email', 'mcc_plant_code', 'plant_code', 'bank_name', 'branch_name', 'latitude', 'longitude', 'employee_code', 'employee_name', 'region_code', 'route_code', 'supervisor_employee_id', 'supervisor_employee_name', 'response_msg', 'response_datetime', 'is_sap_approved'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'occupation', 'age', 'daily_milk_total', 'home_consumption_milk', 'market_surplus_milk', 'annual_milk_pour', 'aadhaar_card_address', 'is_contact_verified', 'is_email_verify', 'is_verify', 'email_relation', 'member_identity_no', 'applicant_relation', 'post_office', 'is_aadhar_verify', 'is_operator_aggre', 'application_no', 'name_as_per_adhar', 'member_status', 'witness_name', 'place'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
}
