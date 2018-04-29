<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblCollectionPoint;
use webvimark\modules\UserManagement\models\User;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_local_milk_sale".
 *
 * @property string $local_milk_sale_code
 * @property string $account_effect
 * @property double $amount
 * @property string $created_at
 * @property string $date
 * @property string $deleted_at
 * @property double $discount
 * @property integer $entry_type
 * @property integer $is_delete
 * @property integer $payment_mode
 * @property double $quantity
 * @property double $cash
 * @property double $coupon
 * @property double $credit
 * @property double $rate
 * @property string $shift_id_id
 * @property string $updated_at
 * @property string $collection_point_code
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property string $member_code
 * @property integer $milk_class
 * @property integer $milk_type
 * @property string $sub_center_code
 * @property string $updated_by
 *
 * @property TblCollectionPoint $collectionPointCode
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblMember $memberCode
 * @property TblAnimalType $milkType
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblLocalMilkSale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_local_milk_sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['local_milk_sale_code'], 'required'],
            [['amount', 'discount', 'quantity', 'rate'], 'number'],
            [['created_at', 'date', 'deleted_at', 'updated_at','is_active','milk_class'], 'safe'],
            [['entry_type', 'is_delete', 'payment_mode', 'milk_type'], 'integer'],
            [['local_milk_sale_code'], 'string', 'max' => 20],
            [['account_effect'], 'string', 'max' => 1],
            [['shift_id'], 'string', 'max' => 10],
            [['collection_point_code', 'member_code'], 'string', 'max' => 11],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code', 'sub_center_code'], 'string', 'max' => 9],
            [['collection_point_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblCollectionPoint::className(), 'targetAttribute' => ['collection_point_code' => 'collection_point_No']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMember::className(), 'targetAttribute' => ['member_code' => 'member_code']],
            [['milk_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type' => 'animal_type_code']],
            [['sub_center_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['sub_center_code' => 'sub_center_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'local_milk_sale_code' => Yii::t('app', 'Local Milk Sale Code'),
            'account_effect' => Yii::t('app', 'Account Effect'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'date' => Yii::t('app', 'Date'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'discount' => Yii::t('app', 'Discount'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'is_active' =>Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'payment_mode' => Yii::t('app', 'Payment Type'),
            'quantity' => Yii::t('app', 'Quantity'),
            'rate' => Yii::t('app', 'Rate'),
            'shift_id' => Yii::t('app', 'Shift'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'collection_point_code' => Yii::t('app', 'Collection Point No'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'member_code' => Yii::t('app', 'Member'),
            'milk_type' => Yii::t('app', 'Milk Type'),
            'sub_center_code' => Yii::t('app', 'Sub Center'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollectionPointCode()
    {
        return $this->hasOne(TblCollectionPoint::className(), ['collection_point_No' => 'collection_point_code']);
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
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShift()
    {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_id']);
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
    public function getMemberCode()
    {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkType()
    {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkClass()
    {
        return $this->hasOne(TblMilkClass::className(), ['id' => 'milk_class']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubCenterCode()
    {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'sub_center_code']);
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
     * @return TblLocalMilkSaleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblLocalMilkSaleQuery(get_called_class());
    }
    
    public function entryType(){
        
        return [0=>'Single Per Shift',1=>'Multiple Per Shift',2=>'Milk Consumer wise entry'];
    }
}
