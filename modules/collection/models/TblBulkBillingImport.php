<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\vsp\models\TblBillHead;

/**
 * This is the model class for table "tbl_bulk_billing_import".
 *
 * @property integer $billing_import_code
 * @property string $billing_type
 * @property string $union_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $customer_name
 * @property string $pan_no
 * @property string $from_date
 * @property string $to_date
 * @property integer $pouring_days
 * @property string $total_qty
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $rec_qty
 * @property string $rec_kg_fat
 * @property string $rec_kg_snf
 * @property string $tds_rate
 * @property string $milk_commitment
 * @property string $qty_till_date
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $net_payable_own_account
 * @property string $net_payable_joint_account
 * @property string $remarks
 * @property string $head_val_seq1
 * @property string $head_val_seq2
 * @property string $head_val_seq3
 * @property string $head_val_seq4
 * @property string $head_val_seq5
 * @property string $head_val_seq6
 * @property string $head_val_seq7
 * @property string $head_val_seq8
 * @property string $head_val_seq9
 * @property string $head_val_seq10
 * @property string $head_val_seq11
 * @property string $head_val_seq12
 * @property string $head_val_seq13
 * @property string $head_val_seq14
 * @property string $head_val_seq15
 * @property integer $status
 * @property string $entry_datetime
 * @property string $pick_datetime
 * @property string $response_datetime
 * @property string $response_msg
 * @property string $uuid
 */
class TblBulkBillingImport extends \yii\db\ActiveRecord {

    public $plant_name, $dcs_name, $membership_status, $vendor_code, $member_signature, $net_payable_amount, $mcc_name, $sahayak_code, $sahayak_cons_after_tds;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bulk_billing_import';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'bmc_code', 'customer_type', 'customer_code', 'uuid', 'response_msg', 'dcs_code', 'customer_name', 'pan_no', 'bank_account_no', 'ifsc', 'remarks', 'billing_type', 'pouring_days', 'status', 'from_date', 'to_date', 'entry_datetime', 'pick_datetime', 'response_datetime', 'total_qty', 'kg_fat', 'kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'tds_rate', 'milk_commitment', 'qty_till_date', 'net_payable_own_account', 'net_payable_joint_account', 'head_val_seq1', 'head_val_seq2', 'head_val_seq3', 'head_val_seq4', 'head_val_seq5', 'head_val_seq6', 'head_val_seq7', 'head_val_seq8', 'head_val_seq9', 'head_val_seq10', 'head_val_seq11', 'head_val_seq12', 'head_val_seq13', 'head_val_seq14', 'head_val_seq15', 'plant_name', 'dcs_name', 'membership_staus', 'vendor_code', 'member_signature', 'net_payable_amount', 'mcc_name', 'sahayak_code', 'sahayak_cons_after_tds'], 'safe'],
                [['bmc_code', 'from_date', 'to_date', 'dcs_code', 'customer_code', 'customer_name', 'pouring_days', 'total_qty', 'bank_account_no', 'ifsc', 'head_val_seq1', 'head_val_seq2', 'head_val_seq3', 'head_val_seq4', 'head_val_seq5', 'head_val_seq6', 'head_val_seq7', 'milk_commitment', 'qty_till_date', 'net_payable_own_account', 'net_payable_joint_account'], 'required', 'on' => ['member_billing_import']],
                [['from_date', 'to_date', 'bmc_code', 'dcs_code', 'customer_name', 'pan_no', 'total_qty', 'kg_fat', 'kg_snf', 'head_val_seq1', 'head_val_seq2', 'head_val_seq3', 'tds_rate', 'head_val_seq4', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'head_val_seq5', 'net_payable_own_account', 'remarks'], 'required', 'on' => ['vendor_billing_import']],
                [['from_date', 'to_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['member_billing_import', 'vendor_billing_import']],
                [['to_date'], 'validateToDate', 'on' => ['member_billing_import', 'vendor_billing_import']],
                [['kg_fat', 'kg_snf', 'total_qty', 'head_val_seq1', 'head_val_seq2', 'head_val_seq3', 'head_val_seq4', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'tds_rate', 'milk_commitment', 'qty_till_date', 'net_payable_own_account', 'net_payable_joint_account', 'head_val_seq5', 'head_val_seq6', 'head_val_seq7'],
                function ($attribute, $params) {
                    $this->$attribute = $this->removeCommas($this->$attribute);
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'billing_import_code' => Yii::t('app', 'Billing Import Code'),
            'billing_type' => Yii::t('app', 'Billing Type'),
            'union_code' => Yii::t('app', 'Union Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'pouring_days' => Yii::t('app', 'Milk pouring days'),
            'total_qty' => Yii::t('app', 'Milk Qty'),
            'kg_fat' => Yii::t('app', 'SC Fat KG'),
            'kg_snf' => Yii::t('app', 'SC Snf KG'),
            'rec_qty' => Yii::t('app', 'Rec Qty'),
            'rec_kg_fat' => Yii::t('app', 'Rec Fat KG'),
            'rec_kg_snf' => Yii::t('app', 'Rec Kg Snf'),
            'tds_rate' => Yii::t('app', 'Rec Snf Kg'),
            'milk_commitment' => Yii::t('app', 'Milk Commitment'),
            'qty_till_date' => Yii::t('app', 'Milk Qty Till Date'),
            'bank_account_no' => Yii::t('app', 'Bank A/C No'),
            'ifsc' => Yii::t('app', 'IFSC code'),
            'net_payable_own_account' => Yii::t('app', 'Direct Transfer To Own A/C'),
            'net_payable_joint_account' => Yii::t('app', 'Direct Transfer To Joint A/C'),
            'remarks' => Yii::t('app', 'Remarks'),
            'head_val_seq1' => Yii::t('app', 'Head Val Seq1'),
            'head_val_seq2' => Yii::t('app', 'Head Val Seq2'),
            'head_val_seq3' => Yii::t('app', 'Head Val Seq3'),
            'head_val_seq4' => Yii::t('app', 'Head Val Seq4'),
            'head_val_seq5' => Yii::t('app', 'Head Val Seq5'),
            'head_val_seq6' => Yii::t('app', 'Head Val Seq6'),
            'head_val_seq7' => Yii::t('app', 'Head Val Seq7'),
            'head_val_seq8' => Yii::t('app', 'Head Val Seq8'),
            'head_val_seq9' => Yii::t('app', 'Head Val Seq9'),
            'head_val_seq10' => Yii::t('app', 'Head Val Seq10'),
            'head_val_seq11' => Yii::t('app', 'Head Val Seq11'),
            'head_val_seq12' => Yii::t('app', 'Head Val Seq12'),
            'head_val_seq13' => Yii::t('app', 'Head Val Seq13'),
            'head_val_seq14' => Yii::t('app', 'Head Val Seq14'),
            'head_val_seq15' => Yii::t('app', 'Head Val Seq15'),
            'status' => Yii::t('app', 'Status'),
            'entry_datetime' => Yii::t('app', 'Entry Datetime'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'response_msg' => Yii::t('app', 'Response Msg'),
            'uuid' => Yii::t('app', 'Uuid'),
        ];
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->to_date) && !empty($this->from_date) && ($this->from_date > $this->to_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date Must be Greater than From Date.'));
            return false;
        }
    }

    public function getLabels($bill_head_for) {
        $bill_head = TblBillHead::find()->select(['bill_head_name', 'sequence_no'])->where(['union_code' => Yii::$app->session->get('Unions'), 'bill_head_for' => $bill_head_for])
                        ->asArray()->all();
        $resultArray = [];
        foreach ($bill_head as $item) {
            $key = str_replace(' ', '_', ('head_val_seq' . $item['sequence_no']));
            $resultArray[$key] = $item['bill_head_name'];
        }
        return $resultArray;
    }

    public function removeCommas($value) {
        return str_replace(',', '', $value);
    }

}
