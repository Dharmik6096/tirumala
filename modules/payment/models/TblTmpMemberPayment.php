<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_tmp_member_payment".
 *
 * @property integer $member_payment_code
 * @property string $union_code
 * @property string $dcs_code
 * @property integer $dcs_payment_cycle_applicabilty_code
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $final_amount
 * @property string $disbus_amount
 * @property string $disbus_date
 * @property string $member_code
 * @property string $payment_date
 * @property string $approved_by
 * @property string $status
 * @property string $transfer_mode
 * @property string $error_code
 * @property string $error_log
 * @property integer $ack
 */
class TblTmpMemberPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $dcs_name;
    public static function tableName()
    {
        return 'tbl_tmp_member_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'dcs_code', 'member_code', 'approved_by', 'status', 'transfer_mode', 'error_code', 'error_log'], 'string'],
            [['dcs_payment_cycle_applicabilty_code', 'ack'], 'integer'],
            [['total_amount', 'total_deduction', 'final_amount', 'disbus_amount'], 'number'],
            [['disbus_date', 'payment_date','dcs_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_payment_code' => 'Member Payment Code',
            'union_code' => 'Union Code',
            'dcs_code' => 'Dcs Code',
            'dcs_payment_cycle_applicabilty_code' => 'Dcs Payment Cycle Applicabilty Code',
            'total_amount' => 'Total Amount',
            'total_deduction' => 'Total Deduction',
            'final_amount' => 'Final Amount',
            'disbus_amount' => 'Disbus Amount',
            'disbus_date' => 'Disbus Date',
            'member_code' => 'Member Code',
            'payment_date' => 'Payment Date',
            'approved_by' => 'Approved By',
            'status' => 'Status',
            'transfer_mode' => 'Transfer Mode',
            'error_code' => 'Error Code',
            'error_log' => 'Error Log',
            'ack' => 'Ack',
        ];
    }

    /**
     * @inheritdoc
     * @return TblTmpMemberPaymentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblTmpMemberPaymentQuery(get_called_class());
    }
    
    //relationship with dcs
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
}
