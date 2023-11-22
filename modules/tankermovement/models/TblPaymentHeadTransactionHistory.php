<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_transporter_head_mapping_history".
 *
 * @property integer $id
 * @property integer $vehicle_transporter_head_mapping_code
 * @property integer $transporter_payment_head_code
 * @property string $vehicle_code
 * @property string $wef_date
 * @property string $amount
 * @property string $remarks
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblPaymentHeadTransactionHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'Tbl_Payment_Head_Transaction_History';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_head_code', 'payment_head_type', 'applicable_date', 'applicable_code', 'applicable_for', 'amount', 'remarks', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'payment_head_code' => Yii::t('app', 'Payment Head Code'),
            'payment_head_type' => Yii::t('app', 'Payment Head Type'),
            'applicable_date' => Yii::t('app', 'Applicable Date'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'amount' => Yii::t('app', 'Amount'),
            'remarks' => Yii::t('app', 'Remarks'),
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
