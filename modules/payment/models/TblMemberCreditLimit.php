<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
/**
 * This is the model class for table "tbl_member_credit_limit".
 *
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
class TblMemberCreditLimit extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_member_credit_limit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance'], 'number'],
            [['member_code', 'union_code', 'dcs_code', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_credit_limit_code' => Yii::t('app', 'Member Credit Limit Code'),
            'balance' => Yii::t('app', 'Balance'),
            'member_code' => Yii::t('app', 'Member Name'),
            'union_code' => Yii::t('app', 'Union'),
            'dcs_code' => Yii::t('app', 'Society'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    
    public function getUnionCode(){
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
    
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
    
    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }
}
