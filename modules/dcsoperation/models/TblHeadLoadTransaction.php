<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\usermanagement\models\User;
/**
 * This is the model class for table "tbl_head_load_transaction".
 *
 * @property string $head_load_transaction_code
 * @property string $created_at
 * @property string $deleted_at
 * @property double $from_km
 * @property double $from_qty
 * @property integer $is_delete
 * @property double $to_km
 * @property double $to_qty
 * @property string $updated_at
 * @property double $value
 * @property string $created_by
 * @property string $deleted_by
 * @property string $head_load_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblHeadLoad $headLoadCode
 * @property User $updatedBy
 */
class TblHeadLoadTransaction extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_head_load_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_km', 'from_qty', 'to_km', 'to_qty', 'value'], 'required'],
            [['created_at', 'deleted_at',  'updated_at','is_delete'], 'safe'],
            [['from_km', 'from_qty', 'to_km', 'to_qty', 'value'], 'number'],
//            [['is_delete'], 'integer'],
            [['to_km'], 'rangeKmValidate'],
            [['to_qty'], 'rangeQtyValidate'],
            [['head_load_transaction_code'], 'string', 'max' => 25],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['head_load_code'], 'string', 'max' => 20],
            [['head_load_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHeadLoad::className(), 'targetAttribute' => ['head_load_code' => 'head_load_code']],
        ];
    }

    public function rangeKmValidate($attribute, $params) {

        if (!empty($this->from_km) && !empty($this->to_km)) {

            if($this->to_km < $this->from_km){
                $this->addError($attribute, 'To Rang cannot be less then From Range.');
                return false;
            }else if($this->to_km == $this->from_km){
                $this->addError($attribute, 'To Rang and From Range cannot be same.');
                return false;
            }
        }
    }
    
    public function rangeQtyValidate($attribute, $params) {

        if (!empty($this->from_qty) && !empty($this->to_qty)) {

            if($this->to_qty < $this->from_qty){
                $this->addError($attribute, 'To Qty cannot be less then From Qty.');
                return false;
            }else if($this->to_qty == $this->from_qty){
                $this->addError($attribute, 'To Qty and From Qty cannot be same.');
                return false;
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'head_load_transaction_code' => Yii::t('app', 'Head Load Transaction Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'from_km' => Yii::t('app', 'From Km'),
            'from_qty' => Yii::t('app', 'From Qty'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'to_km' => Yii::t('app', 'To Km'),
            'to_qty' => Yii::t('app', 'To Qty'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'value' => Yii::t('app', 'Value'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'head_load_code' => Yii::t('app', 'Head Load Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadLoadCode()
    {
        return $this->hasOne(TblHeadLoad::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadTransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblHeadLoadTransactionQuery(get_called_class());
    }
    
    public function getCode(){
        $val= (new \yii\db\Query)
                ->select("MAX(CAST(trim(head_load_transaction_code) AS UNSIGNED)) as head_load_transaction_code")
                ->from('tbl_head_load_transaction')
                ->one();
        $number=(int)$val['head_load_transaction_code']+1;
        return str_pad($number,2,'0',STR_PAD_LEFT);
    }
}
