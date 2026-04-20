<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_payment_cycle_applicability_history".
 *
 * @property integer $id
 * @property integer $payment_cycle_applicabilty_code
 * @property string $dcs_code
 * @property string $from_date
 * @property string $to_date
 * @property integer $is_lock
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblDcsPaymentCycleApplicabilityHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_payment_cycle_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['payment_cycle_applicabilty_code', 'dcs_code'], 'required'],
//            [['payment_cycle_applicabilty_code', 'is_lock'], 'integer'],
//            [['dcs_code', 'operation_type'], 'string'],
                [['payment_cycle_applicabilty_code', 'dcs_payment_cycle_code', 'dcs_code', 'from_date', 'to_date', 'is_lock', 'operation_type', 'history_created_at', 'data_lock', 'data_lock_vsp', 'payment_cycle_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'payment_cycle_applicabilty_code' => Yii::t('app', 'Payment Cycle Applicabilty Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'is_lock' => Yii::t('app', 'Is Lock'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
