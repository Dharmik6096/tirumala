<?php

namespace app\modules\import\models;

use yii\base\Model;
use Yii;

class BackGroundDataImport extends Model {

    public $form_validation_type = 'default';
    public $dcs_code, $ex_member_code, $ref_code, $member_name, $local_name, $father_name, $local_father_name, $surname, $local_surname, $nominee_name, $local_nominee_name, $nominee_relation, $dob, $bloodgroup_code, $gender_code, $qualification_code, $caste_category_code, $religion_code, $total_land, $animal_type_code, $no_of_buffalo, $no_of_cow_cross, $no_of_cow_ind, $member_type_code, $branch_code, $bank_account_no, $beneficiary_name, $mobile_no, $email, $address, $local_address, $pincode, $pan_no, $adhar_no, $annual_income, $hamlet_code, $voter_id, $member_class, $registration_date, $max_allowed_qty;
    public $bmc_code, $applicable_code, $applicable_for, $purchase_rate_code, $dcs_purchase_rate_code, $wef_date, $shift_code;
    public $customer_type, $customer_code, $invoice_date, $payment_mode, $product_code, $quantity, $discount, $no_of_installment, $deduction_start_date;
    public $member_code, $product_sale_rate_code, $product_group_code, $product_name, $tax_code, $is_dpu_product, $dpu_product_code, $is_inhouse, $is_inclusive_tax, $is_saleable, $is_indent;
    public $union_code, $sale_rate, $is_member_rate, $commission, $ifsc, $rate_class, $vendor_code, $sap_farmer_code, $product_type, $remarks, $sap_batch_no, $rate_wharehouse;
    public $shift_applicability, $amount, $allotted_share, $proposed_share, $total_share, $share_amount, $till_date, $folio_no, $member_vendor_code, $from_date, $to_date, $total_qty, $pouring_days, $avg_fat, $avg_snf, $milk_amount, $bonus_criteria, $incentive_amount, $special_code;
    public $product_mrp, $distributor_landing_rate, $sachiv_price, $member_price, $aadesh_master_code;
                function __construct() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['dcs_code', 'member_name'], 'required', 'on' => ['member', 'member_rateclass']],
                [['dob', 'registration_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2017-11-01'), 'skipOnEmpty' => true, 'on' => ['member', 'member_rateclass']],
                [['bmc_code', 'applicable_code', 'applicable_for', 'wef_date', 'shift_applicability'], 'required', 'on' => ['rateapplicability']],
//            [['wef_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2017-11-01'), 'skipOnEmpty' => true, 'on' => ['rateapplicability']],
            [['dcs_purchase_rate_code'], 'required', 'when' => function ($model) {
                    return strtoupper($model->applicable_for) != 'DCS';
                }, 'on' => ['rateapplicability']],
                [['wef_date'], 'convertDateDot', 'on' => ['rateapplicability', 'sale_rate_applicability', 'product_sale_rate', 'product_sale_rate_gyan', 'aadesh_master', 'aadesh_master_applicability']],
                [['wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['rateapplicability', 'sale_rate_applicability', 'product_sale_rate', 'product_sale_rate_gyan', 'aadesh_master', 'aadesh_master_applicability']],
                [['wef_date'], 'validateRateId', 'on' => ['rateapplicability']],
                [['shift_code'], 'in', 'range' => ['M', 'E', 'm', 'e'], 'on' => ['rateapplicability']],
                [['shift_applicability'], 'in', 'range' => ['M', 'E', 'm', 'e', 'A', 'a', 'All', 'all'], 'on' => ['rateapplicability']],
                [['bmc_code', 'customer_code', 'customer_type', 'invoice_date', 'payment_mode', 'product_code', 'quantity'], 'required', 'on' => ['product_sale', 'product_sale_batch']],
                [['invoice_date'], 'convertDateDot', 'on' => ['product_sale', 'product_sale_member', 'product_sale_batch', 'product_sale_member_batch']],
                [['invoice_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['product_sale', 'product_sale_member', 'product_sale_batch', 'product_sale_member_batch']],
                ['payment_mode', 'in', 'range' => ['1', '0'], 'on' => ['product_sale', 'product_sale_member', 'product_sale_batch', 'product_sale_member_batch']],
                [['dcs_code', 'member_code', 'invoice_date', 'payment_mode', 'product_code', 'quantity'], 'required', 'on' => ['product_sale_member']],
                [['bmc_code', 'applicable_code', 'applicable_for', 'wef_date', 'product_sale_rate_code'], 'required', 'on' => ['sale_rate_applicability']],
                [['product_group_code', 'product_name', 'tax_code', 'product_type'], 'required', 'on' => ['product_master']],
                [['is_dpu_product', 'is_inhouse', 'is_inclusive_tax', 'is_saleable', 'is_indent', 'is_member_rate'], 'in', 'range' => ['0', '1'], 'on' => ['product_master']],
                [['dpu_product_code'], 'required', 'when' => function ($model) {
                    return $model->is_dpu_product == 1;
                }, 'on' => ['product_master']],
                [['union_code', 'product_code', 'sale_rate', 'wef_date', 'is_member_rate'], 'required', 'on' => ['product_sale_rate', 'product_sale_rate_gyan', 'aadesh_master']],
                [['commission'], 'required', 'when' => function ($model) {
                    return $model->is_member_rate == 1;
                }, 'on' => ['product_sale_rate', 'product_sale_rate_gyan', 'aadesh_master']
            ],
                ['rate_class', 'in', 'range' => ['A', 'B', 'C'], 'on' => ['member_rateclass']],
                [['deduction_start_date'], 'required', 'on' => ['product_sale', 'product_sale_member'], 'when' => function () {
                    return $this->payment_mode == 1;
                }],
                [['deduction_start_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter deduction date in valid format e.g. 01.12.2018'), 'on' => ['product_sale', 'product_sale_member'], 'when' => function () {
                    return $this->payment_mode == 1;
                }],
                [['member_code'], 'required', 'on' => ['member_share_detail_import']],
                [['member_code'], 'string', 'max' => 16, 'min' => 16, 'skipOnEmpty' => true, 'on' => ['member_share_detail_import']],
                [['till_date'], 'convertDateDot', 'on' => ['member_share_detail_import']],
                [['till_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['member_share_detail_import']],
                [['member_vendor_code', 'from_date', 'to_date', 'total_qty', 'pouring_days', 'avg_fat', 'avg_snf', 'milk_amount', 'bonus_criteria', 'incentive_amount'], 'required', 'on' => ['member_incentive_detail_import']],
                [['from_date', 'to_date'], 'convertDateDot', 'on' => ['member_incentive_detail_import']],
                [['from_date', 'to_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['member_incentive_detail_import']],
                [['dcs_code', 'member_code', 'special_code'], 'required', 'on' => ['member_update_special_code']],
                [['bmc_code', 'applicable_code', 'applicable_for', 'wef_date', 'aadesh_master_code'], 'required', 'on' => ['aadesh_master_applicability']],
//            [['sap_batch_no'], 'required', 'on' => ['product_sale_batch', 'product_sale_member_batch']]
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

    public function convertDateDot($attribute) {
        try {
            $this->$attribute = Yii::$app->controls->view_date($this->$attribute, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->$attribute = '-';
        }
    }

}
