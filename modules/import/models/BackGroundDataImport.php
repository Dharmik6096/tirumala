<?php

namespace app\modules\import\models;

use yii\base\Model;
use Yii;

class BackGroundDataImport extends Model {

    public $form_validation_type = 'default';
    public $dcs_code, $ex_member_code, $ref_code, $member_name, $local_name, $father_name, $local_father_name, $surname, $local_surname, $nominee_name, $local_nominee_name, $nominee_relation, $dob, $bloodgroup_code, $gender_code, $qualification_code, $caste_category_code, $religion_code, $total_land, $animal_type_code, $no_of_buffalo, $no_of_cow_cross, $no_of_cow_ind, $member_type_code, $branch_code, $bank_account_no, $beneficiary_name, $mobile_no, $email, $address, $local_address, $pincode, $pan_no, $adhar_no, $annual_income, $hamlet_code, $voter_id, $member_class, $registration_date, $max_allowed_qty;
    public $bmc_code, $applicable_code, $applicable_for, $purchase_rate_code, $dcs_purchase_rate_code, $wef_date;

    function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
            [['dcs_code', 'member_name'], 'required', 'on' => ['member']],
            [['dob', 'registration_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2017-11-01'), 'skipOnEmpty' => true, 'on' => ['member']],
            [['bmc_code', 'applicable_code', 'applicable_for', 'wef_date'], 'required', 'on' => ['rateapplicability']],
//            [['wef_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2017-11-01'), 'skipOnEmpty' => true, 'on' => ['rateapplicability']],
            [['dcs_purchase_rate_code'], 'required', 'when' => function ($model) {
                    return strtoupper($model->applicable_for) != 'DCS';
                }, 'on' => ['rateapplicability']],
            [['wef_date'], 'validateRateId', 'on' => ['rateapplicability']]
        ];
        $client_rules = Yii::$app->customvalidation->getRules('BackGroundDataImport', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
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

    public function validateRateId($attribute, $params) {
        if (strtolower($this->applicable_for) == 'dcs' && empty($this->purchase_rate_code) && empty($this->dcs_purchase_rate_code)) {
            $this->addError($attribute, Yii::t('app/validation', Yii::t('app', 'DCS') . ' Purchase Rate Code Or Purchase Rate Code Cannot be blank.'));
        }
    }

}
