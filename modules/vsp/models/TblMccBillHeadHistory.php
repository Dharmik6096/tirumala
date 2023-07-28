<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_bill_head_history".
 *
 * @property integer $id
 * @property string $mcc_bill_head_code
 * @property string $bill_head_name
 * @property string $union_code
 * @property integer $is_disburse_allowed
 * @property integer $bill_head_type
 * @property string $general_formula_code
 * @property string $general_formula
 * @property string $general_formula_comma
 * @property integer $default_bill_head_code
 * @property integer $is_default
 * @property integer $is_active
 * @property integer $sequence_no
 * @property string $bill_head_for
 * @property integer $has_slab
 * @property integer $is_hold
 * @property string $payment_cycle_type
 * @property string $calculation_based_on
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMccBillHeadHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_bill_head_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['is_disburse_allowed', 'bill_head_type', 'default_bill_head_code', 'is_default', 'is_active', 'sequence_no', 'has_slab', 'is_hold', 'originating_type'], 'integer'],
            [['created_at', 'updated_at', 'history_created_at', 'milk_type_code'], 'safe'],
            [['mcc_bill_head_code', 'operation_type'], 'string', 'max' => 10],
            [['bill_head_name', 'calculation_based_on'], 'string', 'max' => 100],
            [['union_code'], 'string', 'max' => 3],
            [['general_formula_code', 'bill_head_for'], 'string', 'max' => 20],
            [['general_formula', 'general_formula_comma', 'created_by', 'updated_by'], 'string', 'max' => 255],
            [['payment_cycle_type', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['history_created_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'mcc_bill_head_code' => 'Mcc Bill Head Code',
            'bill_head_name' => 'Bill Head Name',
            'union_code' => 'Union Code',
            'is_disburse_allowed' => 'Is Disburse Allowed',
            'bill_head_type' => 'Bill Head Type',
            'general_formula_code' => 'General Formula Code',
            'general_formula' => 'General Formula',
            'general_formula_comma' => 'General Formula Comma',
            'default_bill_head_code' => 'Default Bill Head Code',
            'is_default' => 'Is Default',
            'is_active' => 'Is Active',
            'sequence_no' => 'Sequence No',
            'bill_head_for' => 'Bill Head For',
            'has_slab' => 'Has Slab',
            'is_hold' => 'Is Hold',
            'payment_cycle_type' => 'Payment Cycle Type',
            'calculation_based_on' => 'Calculation Based On',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'originating_type' => 'Originating Type',
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
