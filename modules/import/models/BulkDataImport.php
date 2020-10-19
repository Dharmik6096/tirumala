<?php

namespace app\modules\import\models;

use yii\base\Model;

class BulkDataImport extends Model {

    public $dcs_code, $ex_member_code, $ref_code, $member_name, $local_name, $father_name, $local_father_name, $surname, $local_surname, $nominee_name, $local_nominee_name, $nominee_relation, $dob, $bloodgroup_code, $gender_code, $qualification_code, $caste_category_code, $religion_code, $total_land, $animal_type_code, $no_of_buffalo, $no_of_cow_cross, $no_of_cow_ind, $member_type_code, $branch_code, $bank_account_no, $beneficiary_name, $mobile_no, $email, $address, $local_address, $pincode, $pan_no, $adhar_no, $annual_income, $hamlet_code, $voter_id, $member_class, $registration_date, $max_allowed_qty;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'address', 'hamlet_code', 'gender_code', 'animal_type_code', 'caste_category_code', 'member_type_code'], 'required', 'on' => ['member']],
        ];
    }

    public function attributeLabels() {
        return [
            'union_code' => \Yii::t('app', 'Union'),
            'plant_code' => \Yii::t('app', 'Plant'),
            'mcc_code' => \Yii::t('app', 'MCC'),
            'bmc_code' => \Yii::t('app', 'BMC'),
            'dcs_code' => \Yii::t('app', 'DCS Code'),
            'dcs_name' => \Yii::t('app', 'DCS Name'),
            'from_date' => \Yii::t('app', 'From Date'),
            'from_shift' => \Yii::t('app', 'From Shift'),
            'to_date' => \Yii::t('app', 'To Date'),
            'to_shift' => \Yii::t('app', 'To Shift'),
            'p_date' => \Yii::t('app', 'As On Date'),
            'date' => \Yii::t('app', 'Date'),
            'shift' => \Yii::t('app', 'Shift'),
            'p_organization_type' => ($this->scenario == 'AmcsSyncPending') ? \Yii::t('app', 'Application') : \Yii::t('app', 'Organization Type'),
            'p_purchase_rate_code' => \Yii::t('app', 'Rate'),
            'vendor_code' => \Yii::t('app', 'Name'),
            'customer_type' => \Yii::t('app', 'Type'),
            'payment_cycle_code' => \Yii::t('app', 'Payment Cycle'),
            'asset_code' => \Yii::t('app', 'Asset'),
        ];
    }

    public function search($params) {
        
    }

}
