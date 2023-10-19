<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\tankermovement\models\TblPartyMaster;
use app\modules\organisation\models\TblUnions; 

class TblPartyPayment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_party_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['party_payment_code', 'union_code', 'payment_type', 'party_master_code', 'from_date', 'to_date', 'disp_qty', 'disp_kg_fat'], 'safe'],
            [['disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'no_of_days'], 'safe'],
            [['total_qty', 'avg_fat', 'avg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'total_addition', 'net_amount'], 'safe'],
            [['adjust_amount', 'adjust_remark', 'final_amount', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc'], 'safe'],
            [['bank_account_no', 'beneficiary_name', 'is_verified', 'payment_date', 'status', 'disburse_amount', 'disburse_date', 'utr_no'], 'safe'],
            [['reference_no', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_at', 'created_by'], 'safe'],
            [['updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['payment_type', 'party_master_code', 'from_date', 'to_date'], 'required', 'on' => ['process']],
            [['from_date'], 'validateDate', 'on' => 'process'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'party_payment_code' => Yii::t('app', 'Party Payment Code'),
            'union_code' => Yii::t('app', 'Union'),
            'payment_type' => Yii::t('app', 'Payment Type'),
            'party_master_code' => Yii::t('app', 'Party Master'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'disp_qty' => Yii::t('app', 'Disp Qty'),
            'disp_kg_fat' => Yii::t('app', 'Disp Kg FAT'),
            'disp_kg_snf' => Yii::t('app', 'Disp Kg SNF'),
            'rec_qty' => Yii::t('app', 'Rec Qty'),
            'rec_kg_fat' => Yii::t('app', 'Rec Kg FAT'),
            'rec_kg_snf' => Yii::t('app', 'Disp Kg Snf'),
            'rd_qty_diff' => Yii::t('app', 'RD Kg SNF'),
            'rd_kg_fat_diff' => Yii::t('app', 'RD Kg FAT Diff'),
            'rd_kg_snf_diff' => Yii::t('app', 'RD Kg SNF Diff'),
            'no_of_days' => Yii::t('app', 'No Of Day'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'avg_fat' => Yii::t('app', 'AvgFat'),
            'avg_snf' => Yii::t('app', 'AvgSnf'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'total_addition' => Yii::t('app', 'Total Addition'),
            'net_amount' => Yii::t('app', 'Net Amount'),
            'adjust_amount' => Yii::t('app', 'Adjust Amount'),
            'adjust_remark' => Yii::t('app', 'Adjust Remark'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'status' => Yii::t('app', 'Status'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'utr_no' => Yii::t('app', 'UTR No'),
            'reference_no' => Yii::t('app', 'Reference no'),
            'process_date' => Yii::t('app', 'Process Date'),
            'reject_reason' => Yii::t('app', 'Reject Reason'),
            'bank_status' => Yii::t('app', 'Bank Status'),
            'payment_transaction_code' => Yii::t('app', 'Payment Transaction'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle'),
        ];
    }
    
    public function validateDate() {
        if (strtotime($this->from_date) > strtotime($this->to_date)) {
            $this->addError('from_date', 'From Date must not be grater than To Date.');
        }
    }
    
    public function getRecords() {
        return $this->find()->where(['union_code' => $this->union_code,
                    'from_date' => $this->from_date,
                    'to_date' => $this->to_date,
                    'payment_type' => $this->payment_type,
                    'party_master_code' => $this->party_master_code,
                    'status' => ['generated', 'processed']
                ])->orderBy(['party_master_code' => SORT_ASC]);
    }
    
    public function getPartyMaster() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'party_master_code']);
    }
    
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
}
