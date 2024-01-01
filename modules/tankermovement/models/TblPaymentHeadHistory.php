<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_head_history".
 *
 * @property integer $id
 * @property integer $transporter_payment_head_code
 * @property string $transporter_payment_head
 * @property integer $type
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblPaymentHeadHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_Payment_head_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_head_code', 'payment_head_type', 'is_active','sequence_no'], 'integer'],
            [['payment_head_name', 'created_by', 'updated_by', 'operation_type'], 'string'],
            [['created_at', 'updated_at', 'history_created_at','union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'payment_head_code' => Yii::t('app', 'Payment Head Code'),
            'payment_head_name' => Yii::t('app', 'Payment Head Name'),
            'payment_head_type' => Yii::t('app', 'Type'),
            'sequence_no' => Yii::t('app', 'Sequence No'),
            'payment_head_for' => Yii::t('app', 'Payment Head For'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
        ];
    }
}
