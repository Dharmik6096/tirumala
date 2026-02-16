<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_payment_cycle_applicability_history".
 *
 * @property integer $id
 * @property integer $payment_cycle_applicabilty_code
 * @property integer $payment_cycle_code
 * @property string $from_date
 * @property string $to_date
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
 * @property string $union_code
 * @property integer $data_lock_bmc
 * @property integer $data_lock_member
 * @property integer $billing_lock_bmc
 * @property integer $billing_lock_member
 * @property integer $sync_lock_bmc
 * @property integer $sync_lock_member
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $updated_by
 */
class TblPaymentCycleApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_payment_cycle_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_cycle_applicabilty_code', 'payment_cycle_code', 'data_lock_bmc', 'data_lock_member', 'billing_lock_bmc', 'billing_lock_member', 'sync_lock_bmc', 'sync_lock_member', 'originating_type'], 'integer'],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'union_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'process_lock_bmc','process_lock_member'], 'safe'],
                [['applicable_code', 'applicable_for', 'applicable_type', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'data_lock_bmc' => Yii::t('app', 'Data Lock Bmc'),
            'data_lock_member' => Yii::t('app', 'Data Lock Member'),
            'billing_lock_bmc' => Yii::t('app', 'Billing Lock Bmc'),
            'billing_lock_member' => Yii::t('app', 'Billing Lock Member'),
            'sync_lock_bmc' => Yii::t('app', 'Sync Lock Bmc'),
            'sync_lock_member' => Yii::t('app', 'Sync Lock Member'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'process_lock_bmc' => Yii::t('app', 'Process Lock BMC'),
            'process_lock_member' => Yii::t('app', 'Process Lock Member'),
        ];
    }

}
