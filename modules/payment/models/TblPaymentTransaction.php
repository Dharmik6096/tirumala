<?php

namespace app\modules\payment\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_payment_transaction".
 *
 * @property integer $payment_transaction_code
 * @property string $union_code
 * @property string $code
 * @property string $type
 * @property integer $dcs_payment_cycle_applicabilty_code
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $final_amount
 * @property string $disburse_amount
 * @property string $disburse_date
 * @property string $payment_date
 * @property string $approved_by
 * @property string $status
 * @property string $transfer_mode
 * @property string $error_code
 * @property string $error_log
 * @property integer $ack
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $qty
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $avg_rate
 * @property integer $dcs_payment_cycle_code
 * @property string $bank_name
 * @property string $bank_code
 * @property string $branch_name
 * @property string $branch_code
 * @property string $ifsc
 * @property string $bank_account_no
 * @property integer $is_verified
 * @property integer $member_count
 */
class TblPaymentTransaction extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_payment_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_payment_cycle_applicabilty_code', 'ack', 'dcs_payment_cycle_code', 'is_verified', 'member_count'], 'integer'],
            [['union_code', 'code', 'type', 'approved_by', 'status', 'transfer_mode', 'error_code', 'error_log', 'created_by', 'updated_by', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no'], 'string'],
            [['total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate'], 'number'],
            [['payment_transaction_code', 'name', 'is_file', 'file_id', 'file_datetime', 'disburse_date', 'payment_date', 'created_at', 'updated_at', 'mobile_no', 'sms_log', 'sms_status', 'sms_timestamp', 'sms_msgid', 'utr_no', 'reference_no', 'process_date', 'reject_reason', 'bank_status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_transaction_code' => Yii::t('app', 'Payment Transaction Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'code' => Yii::t('app', 'Code'),
            'type' => Yii::t('app', 'Type'),
            'dcs_payment_cycle_applicabilty_code' => Yii::t('app', 'Dcs Payment Cycle Applicabilty Code'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'status' => Yii::t('app', 'Status'),
            'transfer_mode' => Yii::t('app', 'Transfer Mode'),
            'error_code' => Yii::t('app', 'Error Code'),
            'error_log' => Yii::t('app', 'Error Log'),
            'ack' => Yii::t('app', 'Ack'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'qty' => Yii::t('app', 'Qty'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'kg_fat' => Yii::t('app', 'Kg Fat'),
            'kg_snf' => Yii::t('app', 'Kg Snf'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'member_count' => Yii::t('app', 'Member Count'),
        ];
    }

    public function getRecords() {
        return $this->find()->where(['is_file' => 0, 'type' => $this->type])->all();
    }

    public function getCode() {
        $val = (new \yii\db\Query)
                ->select(["convert(int,MAX(substring(payment_transaction_code,5,8))) as payment_transaction_code"])
                ->from('tbl_payment_transaction')
                ->where("type='dcs' and union_code =" . trim($this->union_code))
                ->one();
        $code = (int) $val['payment_transaction_code'] + 1;
        return '9' . $this->union_code . str_pad($code, 7, '0', STR_PAD_LEFT);
    }

    public function getMaxCode() {
        $val = (new \yii\db\Query)
                ->select(["convert(bigint,MAX(payment_transaction_code)) as payment_transaction_code"])
                ->from('tbl_payment_transaction')
                ->where("substring(payment_transaction_code,1,1) != '0'")
                ->one();
        if (empty($val['payment_transaction_code'])) {
            $code = 10000000000;
        } else {
            $code = (int) $val['payment_transaction_code'] + 1;
        }
        return $code;
    }

    public function getSmsRecords() {
        return $this->find()->where(['is_file' => 1, 'sms_status' => NULL])->andWhere(['and', ['IS NOT', 'mobile_no', NULL], ['<>', 'mobile_no', '']])->limit(2000)->all();
    }

    public function getPendingData() {
        return $this->find()->select(['tbl_payment_transaction.union_bank_payment_code', 'ubp.bank_code', 'ubp.bank_name', 'ubp.bank_account_no', 'ubp.branch_code', 'ubp.account_holder_name', 'ubp.ftp_username', 'ubp.ftp_password', 'ubp.corporate_code', 'ba.auth_url', 'ba.payment_url', 'ba.reverse_check_url', 'tbl_payment_transaction.file_name', 'tbl_payment_transaction.union_code'])
                        ->innerJoin('tbl_union_bank_payment as ubp', 'ubp.union_bank_payment_code = tbl_payment_transaction.union_bank_payment_code')
                        ->innerJoin('tbl_bank_api_detail ba', 'ubp.union_bank_payment_code= ba.union_bank_payment_code')
                        ->where(['is_file' => 0, 'UPPER(ubp.integration_mode)' => 'API', 'ubp.is_active' => 1, 'ba.is_active' => 1])
                        ->andWhere(['NOT', ['ISNULL(file_name,\'\')' => '']])
                        ->groupBy(['tbl_payment_transaction.file_name', 'tbl_payment_transaction.union_bank_payment_code', 'tbl_payment_transaction.union_code', 'ubp.bank_code', 'ubp.branch_code', 'ubp.account_holder_name', 'ubp.bank_name', 'ubp.bank_account_no', 'ubp.ftp_username', 'ubp.ftp_password', 'ubp.corporate_code', 'ba.auth_url', 'ba.payment_url', 'ba.reverse_check_url'])
                        ->asArray()->all();
    }

    public function updateStatus($condition, $updateData) {
        return $this->updateAll($updateData, $condition);
    }

    public function getCargillPendingData() {
        return $this->find()
                        ->alias('pt')
                        ->select([
                            'pt.payment_transaction_code as TransactionID',
                            'case when ISNULL(b.old_bank_code,\'\')!=\'\' then b.old_bank_code else null end as BenBankCode',
                            'case when ISNULL(pt.ifsc,\'\')!=\'\' then right(pt.ifsc,3)else null end as BenBranchCode',
                            'pt.bank_account_no as BenAccNo',
                            'pt.name as BenAccName',
                            'right(pt.payment_transaction_code,2) as TxnCode',
                            'pt.final_amount as TxnAmount',
                            'ubp.bank_code as DebitBankCode',
                            'ubp.branch_code as DebitBranchCode',
                            'ubp.bank_account_no as DebitAccountNo',
                            'ubp.account_holder_name as DebitAccName',
                            'convert(varchar, getdate(), 12) ValueDate',
                            'case when lower(ISNULL(ubp.corporate_code,\'slips\'))=\'ceft\' then (case when b.old_bank_code=ubp.bank_code then \'CARG\' else \'CEFT\' end) else \'SLIPS\' end as TransactionType',
                            'ba.auth_url',
                            'ba.payment_url',
                            'ba.reverse_check_url',
                            'pt.file_name',
                            'ubp.corporate_code',
                            'pt.type',
                            'pt.union_bank_payment_code',
                            'pt.union_code'
                        ])
                        ->innerJoin('tbl_union_bank_payment as ubp', 'ubp.union_bank_payment_code = pt.union_bank_payment_code')
                        ->innerJoin('tbl_bank_api_detail as ba', 'ubp.union_bank_payment_code = ba.union_bank_payment_code')
                        ->innerJoin('tbl_banks as b', 'b.bank_code = pt.bank_code')
                        ->where([
                            'pt.is_file' => 0,
                            'UPPER(ubp.integration_mode)' => 'API',
                            'ubp.is_active' => 1,
                            'ba.is_active' => 1
                        ])
                        ->andWhere(['NOT', ['ISNULL(pt.file_name, \'\')' => '']])
                        ->orderBy(['pt.payment_transaction_code' => SORT_ASC])
                        ->limit(100)
                        ->asArray()
                        ->all();
    }

    public function updateReverseStatus($response) {
        $data = $this->find()->where(['payment_transaction_code' => $response['txnID']])->one();
        if (!empty($data)) {
            $table = (strtolower($data->type) == 'member') ? 'tbl_member_payment' : 'tbl_vsp_payment';
            $amountColumnName = (strtolower($data->type) == 'member') ? 'final_amount' : 'final_pay';
            $statusColumn = (strtolower($data->type) == 'member') ? 'payment_status' : 'status';
            $date = (strtolower($data->type) == 'member') ? date('Y-m-d H:i:s') : date('Y-m-d');
            $summaryUpadte = (strtolower($data->type) == 'member') ? true : false;
            Yii::$app->db->createCommand()
                    ->update($table, [
                        'disburse_amount' => new Expression("CASE WHEN '" . strtolower($response['status']) . "' != lower('Successful') AND '" . $response['statusCode'] . "' != '000' THEN disburse_amount ELSE " . $amountColumnName . " END"),
                        '' . $statusColumn . '' => new Expression("CASE WHEN '" . strtolower($response['status']) . "' != lower('Successful')AND '" . $response['statusCode'] . "'!= '000' THEN " . $statusColumn . " ELSE 'Disburse' END"),
                        'disburse_date' => $date,
                        'bank_status' => $response['status'],
                        //                       'response_msg' => $response['statusDescription'],
                        'utr_no' => $response['replyID'],
                            ], 'payment_transaction_code = \'' . $response['txnID'] . '\' and lower(' . $statusColumn . ')=\'sent\'')
                    ->execute();
            if ($summaryUpadte) {
                $memberPaymentModel = new TblMemberPayment();
                $memberPaymentModel->payment_cycle_code = $data->payment_cycle_code;
                $memberPaymentModel->dcs_code = $data->dcs_code;
                $memberPaymentModel->union_code = $data->union_code;
                $disburseCount = $memberPaymentModel->find()->select('count(*) as count,payment_status')
                ->where(['union_code' => $this->union_code, 'dcs_payment_cycle_code' => $this->dcs_payment_cycle_code, 'dcs_code' => $this->dcs_code])
                ->groupBy(['payment_status'])->asArray()->all();
                $count = count($disburseCount);
                if ($count == 1 && lower($disburseCount[0]['payment_status']) == 'disburse') {
                    Yii::$app->db->createCommand()
                            ->update('tbl_member_payment_summary', [
                                'disburse_amount' => new Expression('final_amount'),
                                'disburse_date' => date('Y-m-d H:i:s'),
                                'payment_status' => 'Disburse',
                                    ], 'union_code'  => $this->union_code, 'dcs_payment_cycle_code'  => $this->dcs_payment_cycle_code, 'dcs_code'  => $this->dcs_code, 'lower(payment_status)' => 'sent')
                            ->execute();
                }
            }
        }
        return;
    }
}
