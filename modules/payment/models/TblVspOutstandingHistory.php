<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_vsp_outstanding_history".
 *
 * @property integer $id
 * @property integer $vsp_outstanding_code
 * @property string $dcs_code
 * @property string $union_code
 * @property integer $payment_cycle_code
 * @property string $hold_amount
 * @property string $due_amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblVspOutstandingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_outstanding_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vsp_outstanding_code', 'payment_cycle_code'], 'safe'],
            [['dcs_code', 'union_code', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
            [['hold_amount', 'due_amount', 'union_code', 'dcs_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['customer_type', 'customer_code', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'vsp_outstanding_code' => Yii::t('app', 'Vsp Outstanding Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'hold_amount' => Yii::t('app', 'Hold Amount'),
            'due_amount' => Yii::t('app', 'Due Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
