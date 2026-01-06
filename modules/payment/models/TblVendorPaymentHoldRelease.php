<?php

namespace app\modules\payment\models;

use app\models\ChildModel;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use Yii;

/**
 * This is the model class for table "tbl_vendor_payment_hold_release".
 *
 * @property integer $vendor_payment_hold_release_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $customer_name
 * @property string $payment_transaction_code
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $total_qty
 * @property string $rec_qty
 * @property string $rec_fat_kg
 * @property string $rec_snf_kg
 * @property string $amount
 * @property string $addition
 * @property string $deduction
 * @property string $net_payable
 * @property string $adjust_amount
 * @property string $previous_hold
 * @property string $previous_due
 * @property string $hold_amount
 * @property string $final_pay
 * @property string $adjust_remark
 * @property string $disburse_amount
 * @property string $payment_date
 * @property string $disburse_date
 * @property string $status
 * @property string $utr_no
 * @property string $pan_no
 * @property string $reference_no
 * @property string $process_date
 * @property string $reject_reason
 * @property string $registration_date
 * @property string $remarks
 * @property string $bank_name
 * @property string $bank_code
 * @property string $branch_name
 * @property string $branch_code
 * @property string $ifsc
 * @property string $bank_account_no
 * @property integer $is_verified
 * @property string $bank_status
 * @property string $beneficiary_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblVendorPaymentHoldRelease extends ChildModel
{
    public $payment_cycle_code, $otp_code;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vendor_payment_hold_release';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_payment_hold_release_code','union_code','plant_code','mcc_plant_code','bmc_code','route_code','customer_type','customer_code','customer_name','payment_transaction_code','from_datetime','from_shift','to_datetime','to_shift','kg_fat','kg_snf','total_qty','rec_qty','rec_fat_kg','rec_snf_kg','amount','addition','deduction','net_payable','adjust_amount','previous_hold','previous_due','hold_amount','final_pay','adjust_remark','disburse_amount','payment_date','disburse_date','status','utr_no','pan_no','reference_no','process_date','reject_reason','registration_date','remarks','bank_name','bank_code','branch_name','branch_code','ifsc','bank_account_no','is_verified','bank_status','beneficiary_name','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','payment_cycle_code'], 'safe'],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'customer_type'], 'required'],
            [['payment_cycle_code'], 'required', 'on' => ['disbursepayment']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vendor_payment_hold_release_code' => Yii::t('app', 'Vendor Payment Hold Release Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'route_code' => Yii::t('app', 'Route'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'payment_transaction_code' => Yii::t('app', 'Payment Transaction Code'),
            'from_datetime' => Yii::t('app', 'From Datetime'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_datetime' => Yii::t('app', 'To Datetime'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'kg_fat' => Yii::t('app', 'Kg Fat'),
            'kg_snf' => Yii::t('app', 'Kg Snf'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'rec_qty' => Yii::t('app', 'Rec Qty'),
            'rec_fat_kg' => Yii::t('app', 'Rec Fat Kg'),
            'rec_snf_kg' => Yii::t('app', 'Rec Snf Kg'),
            'amount' => Yii::t('app', 'Amount'),
            'addition' => Yii::t('app', 'Addition'),
            'deduction' => Yii::t('app', 'Deduction'),
            'net_payable' => Yii::t('app', 'Net Payable'),
            'adjust_amount' => Yii::t('app', 'Adjust Amount'),
            'previous_hold' => Yii::t('app', 'Previous Hold'),
            'previous_due' => Yii::t('app', 'Previous Due'),
            'hold_amount' => Yii::t('app', 'Hold Amount'),
            'final_pay' => Yii::t('app', 'Final Pay'),
            'adjust_remark' => Yii::t('app', 'Adjust Remark'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'status' => Yii::t('app', 'Status'),
            'utr_no' => Yii::t('app', 'Utr No'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'reference_no' => Yii::t('app', 'Reference No'),
            'process_date' => Yii::t('app', 'Process Date'),
            'reject_reason' => Yii::t('app', 'Reject Reason'),
            'registration_date' => Yii::t('app', 'Registration Date'),
            'remarks' => Yii::t('app', 'Remarks'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'bank_status' => Yii::t('app', 'Bank Status'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getRecords() {
        return $this->find()
                ->where([
                    'union_code' => $this->union_code,
                    'CAST(from_datetime AS DATE)' => date('Y-m-d', strtotime($this->from_datetime)),
                    'CAST(to_datetime AS DATE)' => date('Y-m-d', strtotime($this->to_datetime)),
                    'bmc_code' => $this->bmc_code,
                    'customer_type' => $this->customer_type,
                    'status' => ['generated', 'processed'] 
                ])
                ->orderBy(['net_payable' => 'ASC']);

    }
}
