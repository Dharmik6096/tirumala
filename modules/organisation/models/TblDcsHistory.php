<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_history".
 *
 * @property string $id
 * @property string $address
 * @property string $local_address
 * @property integer $allow_multi_family_member
 * @property string $bank_account_no
 * @property string $contact_person
 * @property string $created_at
 * @property string $dcs_code
 * @property string $dcs_code_ex
 * @property string $dcs_name
 * @property string $local_name
 * @property string $dcs_short_name
 * @property string $local_short_name
 * @property string $destination_code
 * @property integer $destination_type
 * @property string $effective_date
 * @property string $email
 * @property string $history_created_at
 * @property string $ifsc
 * @property integer $is_active
 * @property integer $is_bmc
 * @property string $mobile_no
 * @property string $operation_type
 * @property string $pan_no
 * @property string $phone_no
 * @property string $pincode
 * @property string $registration_code
 * @property string $registration_date
 * @property string $service_tax
 * @property string $tin_no
 * @property string $updated_at
 * @property string $bank_code
 * @property string $branch_code
 * @property string $created_by
 * @property integer $dcs_type_code
 * @property string $district_code
 * @property string $hamlet_code
 * @property string $route_code
 * @property string $state_code
 * @property string $sub_district_code
 * @property string $union_code
 * @property string $updated_by
 * @property string $village_code
 * @property string $upi_no
 * @property string $organisation_type_code
 * @property string $scheme_type_code
 * @property string $is_registerd
 */
class TblDcsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at', 'is_active', 'local_name', 'local_short_name', 'local_address', 'organisation_type_code', 'scheme_type_code', 'is_registered', 'data_post_status', 'origination_type', 'credit_sale_allow', 'default_milk_type', 'dpu_type', 'history_created_by', 'is_chiller', 'machine_owned', 'sim_network', 'sim_no', 'is_aadhar_verify'], 'safe'],
                [['address', 'bank_code', 'district_code', 'hamlet_code', 'route_code', 'state_code', 'sub_district_code', 'contact_person', 'email', 'phone_no', 'dcs_short_name', 'dcs_code', 'dcs_code_ex', 'mobile_no', 'destination_code', 'dcs_name', 'bank_account_no', 'ifsc', 'pan_no', 'registration_code', 'service_tax', 'tin_no', 'destination_type', 'dcs_type_code', 'allow_multi_family_member', 'is_bmc', 'bmc_code', 'village_code', 'effective_date', 'registration_date', 'upi_no', 'branch_code', 'union_code', 'valid_from', 'cheque_number', 'cheque_amount', 'is_security_cheque', 'cheque_bank', 'security_return_date', 'security_return_amt', 'security_return_mode'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'is_weight_manual', 'is_quality_manual', 'ref_code'], 'safe'],
                [['vendor_code', 'auto_code', 'sap_vendor_code', 'password', 'antibiotic_check', 'ts_code_m', 'ts_code_e'], 'safe'],
                [['data_post_id', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime', 'cutoff', 'lower_milk_type', 'cutoff_val'], 'safe'],
                [['block_code', 'local_contact_person', 'logo_path', 'punch_line', 'mapped_village_no', 'secretory_info', 'gst_no', 'fssi', 'mcc_plant_code', 'plant_code', 'bank_name', 'branch_name', 'is_dispatch_mandate', 'originating_org_code', 'originating_org_type', 'originating_type', 'member_rate_code', 'old_bmc_code', 'old_mcc_plant_code', 'old_route_code', 'DPUVersionNo', 'rate_flag', 'is_name_request', 'morning_kms', 'evening_kms', 'ccenter_code', 'center_code', 'sap_center_code', 'rate_chart_code', 'mfile_digit', 'aadhaar_no', 'pincode', 'emilk_sync_status', 'emilk_sync_timestamp', 'fssi_expiry_date', 'type_of_dcs'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
                /* 'id' => Yii::t('app', 'ID'),
                  'address' => Yii::t('app', 'Address'),
                  'allow_multi_family_member' => Yii::t('app', 'Allow Multi Family Member'),
                  'bank_account_no' => Yii::t('app', 'Bank Accout No'),
                  'contact_person' => Yii::t('app', 'Contact Person'),
                  'created_at' => Yii::t('app', 'Created At'),
                  'dcs_code' => Yii::t('app', 'Dcs Code'),
                  'dcs_code_ex' => Yii::t('app', 'Dcs Code Ex'),
                  'dcs_name' => Yii::t('app', 'Dcs Name'),
                  'dcs_short_name' => Yii::t('app', 'Dcs Short Name'),
                  'destination_code' => Yii::t('app', 'Destination Code'),
                  'destination_type' => Yii::t('app', 'Destination Type'),
                  'effective_date' => Yii::t('app', 'Effective Date'),
                  'email' => Yii::t('app', 'Email'),
                  'history_created_at' => Yii::t('app', 'History Created At'),
                  'ifsc' => Yii::t('app', 'Ifsc'),
                  'is_active' => Yii::t('app', 'Is Active'),
                  'is_bmc' => Yii::t('app', 'Is Bmc'),
                  'mobile_no' => Yii::t('app', 'Mobile No'),
                  'operation_type' => Yii::t('app', 'Operation Type'),
                  'pan_no' => Yii::t('app', 'Pan No'),
                  'phone_no' => Yii::t('app', 'Phone No'),
                  'pincode' => Yii::t('app', 'Pincode'),
                  'registration_code' => Yii::t('app', 'Registration Code'),
                  'registration_date' => Yii::t('app', 'Registration Date'),
                  'service_tax' => Yii::t('app', 'Service Tax'),
                  'tin_no' => Yii::t('app', 'Tin No'),
                  'updated_at' => Yii::t('app', 'Updated At'),
                  'bank_code' => Yii::t('app', 'Bank Code'),
                  'branch_code' => Yii::t('app', 'Branch Code'),
                  'created_by' => Yii::t('app', 'Created By'),
                  'dcs_type_code' => Yii::t('app', 'Dcs Type Code'),
                  'district_code' => Yii::t('app', 'District Code'),
                  'hamlet_code' => Yii::t('app', 'Hamlet Code'),
                  'route_code' => Yii::t('app', 'Route Code'),
                  'state_code' => Yii::t('app', 'State Code'),
                  'sub_district_code' => Yii::t('app', 'Sub District Code'),
                  'union_code' => Yii::t('app', 'Union Code'),
                  'updated_by' => Yii::t('app', 'Updated By'),
                  'village_code' => Yii::t('app', 'Village Code'),
                  'upi_no' => Yii::t('app', 'UPI No.'), */
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsHistoryQuery(get_called_class());
    }

}
