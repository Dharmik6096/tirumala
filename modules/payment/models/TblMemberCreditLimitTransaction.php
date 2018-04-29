<?php

namespace app\modules\payment\models;

use Yii;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "tbl_member_credit_limit_transaction".
 *
 * @property integer $member_credit_limit_transaction_code
 * @property integer $member_credit_limit_code
 * @property string $balance
 * @property string $new_value
 * @property string $old_value
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMemberCreditLimitTransaction extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public $union_code,$dcs_code,$member_code;
    public static function tableName()
    {
        return 'tbl_member_credit_limit_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_credit_limit_code'], 'required'],
            [['member_credit_limit_code'], 'integer'],
            [['balance', 'new_value', 'old_value'], 'number'],
            [['created_at', 'updated_at','transaction_type'], 'safe'],
            [['created_by', 'updated_by'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_credit_limit_transaction_code' => Yii::t('app', 'Member Credit Limit Transaction Code'),
            'member_credit_limit_code' => Yii::t('app', 'Member Credit Limit Code'),
            'balance' => Yii::t('app', 'Balance'),
            'new_value' => Yii::t('app', 'New Value'),
            'old_value' => Yii::t('app', 'Old Value'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'transaction_type' => Yii::t('app', 'Transaction Type'),
        ];
    }
    
    public function search($member_credit_limit_code){
        
        $query = TblMemberCreditLimitTransaction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->andFilterWhere([
            'member_credit_limit_code' => $member_credit_limit_code,
        ]);
        return $dataProvider;
    }
    
    public function getMemberCreditLimit() {
        return $this->hasOne(TblMemberCreditLimit::className(), ['member_credit_limit_code' => 'member_credit_limit_code']);
    }
}
