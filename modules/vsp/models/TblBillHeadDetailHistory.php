<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_bill_head_detail_history".
 *
 * @property integer $id
 * @property string $bill_head_detail_code
 * @property string $union_code
 * @property string $bill_head_code
 * @property integer $payment_cycle_code
 * @property string $dcs_code
 * @property string $amount
 * @property integer $is_installment
 * @property integer $no_installment
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblBillHeadDetailHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head_detail_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bill_head_detail_code', 'bill_head_code', 'payment_cycle_code', 'dcs_code', 'is_active'], 'safe'],
            [['bill_head_detail_code', 'union_code', 'bill_head_code', 'dcs_code', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
            [['payment_cycle_code', 'is_installment', 'no_installment', 'is_active'], 'safe'],
            [['amount'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
            [['customer_type', 'customer_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'bill_head_detail_code' => 'Bill Head Detail Code',
            'union_code' => 'Union Code',
            'bill_head_code' => 'Bill Head Code',
            'payment_cycle_code' => 'Payment Cycle Code',
            'dcs_code' => 'Dcs Code',
            'amount' => 'Amount',
            'is_installment' => 'Is Installment',
            'no_installment' => 'No Installment',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'operation_type' => 'Operation Type',
            'history_created_at' => 'History Created At',
            'history_created_by' => 'History Created By',
        ];
    }

}
