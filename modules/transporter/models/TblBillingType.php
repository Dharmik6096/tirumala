<?php

namespace app\modules\transporter\models;

use Yii;

/**
 * This is the model class for table "tbl_billing_type".
 *
 * @property integer $billing_type_code
 * @property string $billing_type
 * @property integer $is_active
 */
class TblBillingType extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_billing_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['billing_type'], 'string'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'billing_type_code' => Yii::t('app', 'Billing Type Code'),
            'billing_type' => Yii::t('app', 'Billing Type'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
}
