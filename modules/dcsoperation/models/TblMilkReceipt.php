<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsChillingCenter;
use app\modules\globalmaster\models\TblMilkQualityType;

/**
 * This is the model class for table "tbl_milk_receipt".
 *
 * @property string $milk_receipt_code
 * @property double $amount
 * @property string $challan_no
 * @property string $created_at
 * @property string $deleted_at
 * @property string $flg_sentbox_entry
 * @property string $from_date
 * @property string $from_shift
 * @property integer $is_active
 * @property integer $is_delete
 * @property integer $nos_of_can
 * @property double $rate
 * @property string $rate_calculation_date_time
 * @property double $receipt_acidity
 * @property double $receipt_clr
 * @property double $receipt_density
 * @property double $receipt_fat
 * @property double $receipt_freezing_point
 * @property double $receipt_lactose
 * @property integer $receipt_local_type
 * @property string $receipt_org_code
 * @property double $receipt_protein
 * @property double $receipt_qty
 * @property double $receipt_snf
 * @property double $receipt_temp
 * @property double $receipt_water
 * @property string $to_date
 * @property string $to_shift
 * @property string $updated_at
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property integer $milk_quality_type_code
 * @property integer $milk_type
 * @property integer $receipt_org_chilling_center
 * @property string $receipt_org_id
 * @property string $sub_center_code
 * @property string $union_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblMilkQualityType $milkQualityTypeCode
 * @property TblAnimalType $milkType
 * @property TblDcsChillingCenter $receiptOrgChillingCenter
 * @property TblDcs $receiptOrg
 * @property TblSubCenter $subCenterCode
 * @property TblUnions $unionCode
 * @property User $updatedBy
 */
class TblMilkReceipt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_milk_receipt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['milk_receipt_code'], 'required'],
            [['amount', 'rate', 'receipt_acidity', 'receipt_clr', 'receipt_density', 'receipt_fat', 'receipt_freezing_point', 'receipt_lactose', 'receipt_protein', 'receipt_qty', 'receipt_snf', 'receipt_temp', 'receipt_water'], 'number'],
            [['created_at', 'deleted_at', 'from_date', 'rate_calculation_date_time', 'to_date', 'updated_at'], 'safe'],
            [['is_active', 'is_delete', 'nos_of_can', 'receipt_local_type', 'milk_quality_type_code', 'milk_type', 'receipt_org_chilling_center'], 'integer'],
            [['milk_receipt_code', 'challan_no'], 'string', 'max' => 20],
            [[ 'from_shift',  'to_shift'], 'string', 'max' => 1],
            [['receipt_org_code'], 'string', 'max' => 255],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code', 'receipt_org_id', 'sub_center_code'], 'string', 'max' => 9],
            [['union_code'], 'string', 'max' => 3],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['milk_quality_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkQualityType::className(), 'targetAttribute' => ['milk_quality_type_code' => 'milk_quality_type_code']],
            [['milk_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type' => 'animal_type_code']],
            [['receipt_org_chilling_center'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsChillingCenter::className(), 'targetAttribute' => ['receipt_org_chilling_center' => 'id']],
            [['receipt_org_id'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['receipt_org_id' => 'dcs_code']],
            [['sub_center_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['sub_center_code' => 'sub_center_code']],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'milk_receipt_code' => Yii::t('app', 'Milk Receipt Code'),
            'amount' => Yii::t('app', 'Amount'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'nos_of_can' => Yii::t('app', 'Nos Of Can'),
            'rate' => Yii::t('app', 'Rate'),
            'rate_calculation_date_time' => Yii::t('app', 'Rate Calculation Date Time'),
            'receipt_acidity' => Yii::t('app', 'Receipt Acidity'),
            'receipt_clr' => Yii::t('app', 'Receipt Clr'),
            'receipt_density' => Yii::t('app', 'Receipt Density'),
            'receipt_fat' => Yii::t('app', 'Receipt Fat'),
            'receipt_freezing_point' => Yii::t('app', 'Receipt Freezing Point'),
            'receipt_lactose' => Yii::t('app', 'Receipt Lactose'),
            'receipt_local_type' => Yii::t('app', 'Receipt Local Type'),
            'receipt_org_code' => Yii::t('app', 'Receipt Org Code'),
            'receipt_protein' => Yii::t('app', 'Receipt Protein'),
            'receipt_qty' => Yii::t('app', 'Receipt Qty'),
            'receipt_snf' => Yii::t('app', 'Receipt Snf'),
            'receipt_temp' => Yii::t('app', 'Receipt Temp'),
            'receipt_water' => Yii::t('app', 'Receipt Water'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'milk_type' => Yii::t('app', 'Milk Type'),
            'receipt_org_chilling_center' => Yii::t('app', 'Receipt Org Chilling Center'),
            'receipt_org_id' => Yii::t('app', 'Receipt Org'),
            'sub_center_code' => Yii::t('app', 'Sub Center'),
            'union_code' => Yii::t('app', 'Union'),
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
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
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
    public function getMilkQualityTypeCode()
    {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
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
    public function getReceiptOrgChillingCenter()
    {
        return $this->hasOne(TblDcsChillingCenter::className(), ['id' => 'receipt_org_chilling_center']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiptOrg()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'receipt_org_id']);
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
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
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
     * @return TblMilkReceiptQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMilkReceiptQuery(get_called_class());
    }
}
