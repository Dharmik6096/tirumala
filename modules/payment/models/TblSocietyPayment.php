<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_society_payment".
 *
 * @property integer $society_payment_code
 * @property string $society_code
 * @property string $total_amount
 * @property integer $payment_cycle_applicabilty_code
 * @property string $payment_date
 */
class TblSocietyPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_society_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['society_code'], 'string'],
            [['total_amount'], 'number'],
            [['payment_cycle_applicabilty_code'], 'integer'],
            [['payment_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'society_payment_code' => 'Society Payment Code',
            'society_code' => 'Society Code',
            'total_amount' => 'Total Amount',
            'payment_cycle_applicabilty_code' => 'Payment Cycle Applicabilty Code',
            'payment_date' => 'Payment Date',
        ];
    }

    /**
     * @inheritdoc
     * @return TblSocietyPaymentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblSocietyPaymentQuery(get_called_class());
    }
}
