<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_union_credit_limit_history".
 *
 * @property integer $id
 * @property integer $union_credit_limit_code
 * @property string $union_code
 * @property integer $credit_type
 * @property integer $value
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblUnionCreditLimitHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_union_credit_limit_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'union_credit_limit_code', 'credit_type', 'credit_value'], 'safe'],
            [['union_code', 'created_by', 'updated_by', 'operation_type'], 'safe'],
            [['wef_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'union_credit_limit_code' => Yii::t('app', 'Union Credit Limit Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'credit_type' => Yii::t('app', 'Credit Type'),
            'credit_value' => Yii::t('app', 'Credit Value'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
