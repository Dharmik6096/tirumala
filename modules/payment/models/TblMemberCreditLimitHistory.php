<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_member_credit_limit_history".
 *
 * @property integer $member_credit_limit_history_code
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $member_credit_limit_code
 * @property string $balance
 * @property string $member_code
 * @property string $union_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMemberCreditLimitHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_member_credit_limit_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_credit_limit_code'], 'required'],
            [['member_credit_limit_code'], 'integer'],
            [['id','history_created_at', 'created_at', 'updated_at'], 'safe'],
            [['operation_type', 'member_code', 'union_code', 'dcs_code', 'created_by', 'updated_by'], 'string'],
            [['balance'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Id'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'member_credit_limit_code' => Yii::t('app', 'Member Credit Limit Code'),
            'balance' => Yii::t('app', 'Balance'),
            'member_code' => Yii::t('app', 'Member Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
