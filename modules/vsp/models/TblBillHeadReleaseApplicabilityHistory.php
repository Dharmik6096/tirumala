<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_bill_head_release_applicability_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property integer $bill_head_release_applicabilty_code
 * @property string $bill_head_code
 * @property string $bill_head_for
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $bmc_code
 * @property string $from_date
 * @property string $to_date
 * @property integer $payment_cycle_code
 * @property string $previous_amount
 * @property string $current_amount
 * @property string $total_amount
 * @property integer $is_processed
 * @property integer $is_disbursed
 * @property string $disburse_date
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblBillHeadReleaseApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bill_head_release_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'from_date', 'to_date', 'disburse_date', 'created_at', 'updated_at'], 'safe'],
                [['bill_head_release_applicabilty_code', 'payment_cycle_code', 'is_processed', 'is_disbursed', 'originating_type'], 'safe'],
                [['previous_amount', 'current_amount', 'total_amount'], 'safe'],
                [['operation_type', 'bill_head_code'], 'safe'],
                [['history_created_by', 'created_by', 'updated_by'], 'safe'],
                [['bill_head_for', 'applicable_code', 'applicable_for'], 'safe'],
                [['bmc_code'], 'safe'],
                [['union_code'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'bill_head_release_applicabilty_code' => Yii::t('app', 'Bill Head Release Applicabilty Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'bill_head_for' => Yii::t('app', 'Bill Head For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'previous_amount' => Yii::t('app', 'Previous Amount'),
            'current_amount' => Yii::t('app', 'Current Amount'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'is_processed' => Yii::t('app', 'Is Processed'),
            'is_disbursed' => Yii::t('app', 'Is Disbursed'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
