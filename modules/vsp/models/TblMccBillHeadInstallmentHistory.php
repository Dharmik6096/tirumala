<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_bill_head_installment_history".
 *
 * @property integer $id
 * @property integer $bill_head_installment_code
 * @property integer $bill_head_detail_code
 * @property string $bill_head_code
 * @property string $dcs_code
 * @property string $installement_cycle
 * @property string $installment_amount
 * @property string $installment_date
 * @property integer $payment_cycle_code
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblBillHeadInstallmentHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head_installment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bill_head_installment_code', 'bill_head_detail_code', 'payment_cycle_code', 'bill_head_for', 'payment_cycle_type'], 'safe'],
                [['bill_head_code', 'dcs_code', 'installement_cycle', 'operation_type', 'history_created_by'], 'safe'],
                [['installment_amount'], 'safe'],
                [['installment_date', 'history_created_at'], 'safe'],
                [['customer_type', 'customer_code', 'union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'bill_head_installment_code' => 'Bill Head Installment Code',
            'bill_head_detail_code' => 'Bill Head Detail Code',
            'bill_head_code' => 'Bill Head Code',
            'dcs_code' => 'Dcs Code',
            'installement_cycle' => 'Installement Cycle',
            'installment_amount' => 'Installment Amount',
            'installment_date' => 'Installment Date',
            'payment_cycle_code' => 'Payment Cycle Code',
            'operation_type' => 'Operation Type',
            'history_created_at' => 'History Created At',
            'history_created_by' => 'History Created By',
        ];
    }

}
