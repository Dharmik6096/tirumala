<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblUnions;
/**
 * This is the model class for table "tbl_union_credit_limit".
 *
 * @property integer $union_credit_limit_code
 * @property string $union_code
 * @property integer $credit_type
 * @property integer $credit_value
 * @property string $wef_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblUnionCreditLimit extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public $old_credit_type;
    public static function tableName()
    {
        return 'tbl_union_credit_limit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'created_by', 'updated_by'], 'string'],
            [['union_code', 'credit_type', 'credit_value', 'wef_date'], 'required'],
            [['credit_type', 'credit_value'], 'integer'],
            [['wef_date', 'created_at', 'updated_at','old_credit_type'], 'safe'],
            [['union_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'union_credit_limit_code' => Yii::t('app', 'Union Credit Limit Code'),
            'union_code' => Yii::t('app', 'Union'),
            'credit_type' => Yii::t('app', 'Credit Type'),
            'credit_value' => Yii::t('app', 'Credit Value'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
      
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
}
