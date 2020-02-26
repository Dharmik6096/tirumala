<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_rejection_reason".
 *
 * @property integer $rejection_reason_code
 * @property string $rejection_reason
 * @property integer $is_active
 */
class TblRejectionReason extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_rejection_reason';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rejection_reason'], 'string'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rejection_reason_code' => Yii::t('app', 'Rejection Reason Code'),
            'rejection_reason' => Yii::t('app', 'Rejection Reason'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
}
