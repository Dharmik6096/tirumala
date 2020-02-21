<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_payment_cycle_history".
 *
 * @property integer $id
 * @property integer $payment_cycle_code
 * @property string $union_code
 * @property integer $interval_value
 * @property string $from_date
 * @property string $from_shift
 * @property string $to_date
 * @property string $to_shift
 * @property integer $is_active
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
class TblPaymentCycleHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_payment_cycle_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_cycle_code', 'interval_value', 'is_active', 'originating_type'], 'safe'],
                [['union_code', 'from_shift', 'to_shift', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
                [['from_date', 'to_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'interval_value' => Yii::t('app', 'Interval Value'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'is_active' => Yii::t('app', 'Is Active'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
