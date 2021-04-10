<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\payment\models\TblPaymentCycle;

/**
 * This is the model class for table "tbl_bank_payment_log".
 *
 * @property integer $log_id
 * @property string $union_code
 * @property string $file_path
 * @property integer $dcs_payment_cycle_code
 * @property string $status
 * @property string $payment_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblBankPaymentLog extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bank_payment_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // [['log_id'], 'safe'],
            [['dcs_payment_cycle_code'], 'integer'],
            [['union_code', 'file_path', 'created_by', 'updated_by'], 'string'],
            [['payment_date', 'created_at', 'updated_at', 'status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'union_code' => Yii::t('app', 'Union Code'),
            'file_path' => Yii::t('app', 'File Path'),
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'status' => Yii::t('app', 'Status'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getFileRecord() {
        return $this->find()->where(['status' => [1, 2]])->all();
    }

    public function getUnionBankPaymentCode() {
        return $this->hasOne(TblUnionBankPayment::className(), ['union_code' => 'union_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getFromDate() {
        return $this->hasOne(TblPaymentCycle::className(), ['payment_cycle_code' => 'dcs_payment_cycle_code']);
    }

}
