<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_bill_head_history".
 *
 * @property integer $id
 * @property string $bill_head_code
 * @property string $bill_head_name
 * @property integer $is_default
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 * @property integer $is_disburse_allowed
 * @property integer $bill_head_type
 * @property string $general_formula_code
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 * @property string $general_formula_comma
 * @property integer $default_bill_head_code
 * @property integer $sequence_no
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblBillHeadHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bill_head_code', 'bill_head_name', 'created_by', 'updated_by', 'union_code', 'general_formula_code', 'operation_type', 'history_created_by', 'general_formula_comma', 'originating_org_code', 'originating_org_type'], 'string'],
                [['is_default', 'is_active', 'is_disburse_allowed', 'bill_head_type', 'default_bill_head_code', 'sequence_no', 'originating_type'], 'integer'],
                [['created_at', 'updated_at', 'history_created_at', 'milk_type_code'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'bill_head_for', 'calculation_based_on', 'is_hold', 'payment_cycle_type', 'sap_seq_no', 'is_reserved', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'bill_head_name' => Yii::t('app', 'Bill Head Name'),
            'is_default' => Yii::t('app', 'Is Default'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union Code'),
            'is_disburse_allowed' => Yii::t('app', 'Is Disburse Allowed'),
            'bill_head_type' => Yii::t('app', 'Bill Head Type'),
            'general_formula_code' => Yii::t('app', 'General Formula Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'general_formula_comma' => Yii::t('app', 'General Formula Comma'),
            'default_bill_head_code' => Yii::t('app', 'Default Bill Head Code'),
            'sequence_no' => Yii::t('app', 'Sequence No'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'is_reserved' => Yii::t('app', 'Is Reserved'),
        ];
    }

}
