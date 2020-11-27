<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\transporter\models\TblTransporterPaymentHead;

/**
 * This is the model class for table "tbl_transporter_payment_head_detail".
 *
 * @property integer $head_detail_code
 * @property integer $transporter_payment_code
 * @property integer $transporter_payment_head_code
 * @property integer $type
 * @property string $amount
 */
class TblTransporterPaymentHeadDetail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transporter_payment_head_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transporter_payment_code', 'transporter_payment_head_code', 'type'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'head_detail_code' => Yii::t('app', 'Head Detail Code'),
            'transporter_payment_code' => Yii::t('app', 'Transporter Payment Code'),
            'transporter_payment_head_code' => Yii::t('app', 'Payment Head'),
            'type' => Yii::t('app', 'Head Type'),
            'amount' => Yii::t('app', 'Amount'),
        ];
    }

    public function getPaymentHeadCode() {
        return $this->hasOne(TblTransporterPaymentHead::className(), ['transporter_payment_head_code' => 'transporter_payment_head_code']);
    }

}
