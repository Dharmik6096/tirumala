<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_bonus_payment_head".
 *
 * @property integer $bonus_payment_head_code
 * @property integer $bonus_payment_code
 * @property string $bill_head_code
 * @property integer $bill_head_type
 * @property string $amount
 * @property string $general_formula
 */
class TblBonusPaymentHead extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bonus_payment_head';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bonus_payment_code'], 'required'],
            [['bonus_payment_code', 'bill_head_type'], 'integer'],
            [['amount'], 'number'],
            [['bill_head_code'], 'string', 'max' => 10],
            [['general_formula'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bonus_payment_head_code' => Yii::t('app', 'Bonus Payment Head Code'),
            'bonus_payment_code' => Yii::t('app', 'Bonus Payment Code'),
            'bill_head_code' => Yii::t('app', 'Bill Head Code'),
            'bill_head_type' => Yii::t('app', 'Bill Head Type'),
            'amount' => Yii::t('app', 'Amount'),
            'general_formula' => Yii::t('app', 'General Formula'),
        ];
    }
}
