<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_subcenter_bmc_history".
 *
 * @property integer $id
 * @property string $bmc_code
 * @property string $created_at
 * @property double $extra_tank_capacity
 * @property integer $has_extra_tank
 * @property string $history_created_at
 * @property string $installment_allocation_date
 * @property integer $is_active
 * @property string $model
 * @property string $operation_type
 * @property string $remarks
 * @property string $serial_no
 * @property string $updated_at
 * @property integer $capacity
 * @property string $created_by
 * @property integer $manufacturer_code
 * @property string $subcenter_code
 * @property string $updated_by
 *
 * @property TblCapacity $capacity0
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblManufacturer $manufacturerCode
 * @property TblSubCenter $subcenterCode
 * @property User $updatedBy
 */
class TblSubcenterBmcHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_subcenter_bmc_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active', 'is_delete'], 'safe'],
            [['extra_tank_capacity','manufacturer_code','subcenter_code','has_extra_tank',  'capacity', 'manufacturer_code','bmc_code','model', 'serial_no','remarks','subcenter_code'], 'safe'],
//            [['is_active', 'created_at', 'history_created_at', 'installment_allocation_date', 'updated_at'], 'safe'],
//            [['extra_tank_capacity'], 'number'],
//            [['has_extra_tank',  'capacity', 'manufacturer_code'], 'integer'],
//            [['bmc_code'], 'string', 'max' => 12],
//            [['model', 'serial_no'], 'string', 'max' => 255],
//            [['operation_type'], 'string', 'max' => 10],
//            [['remarks'], 'string', 'max' => 250],
//            [['created_by', 'updated_by'], 'string', 'max' => 14],
//            [['subcenter_code'], 'string', 'max' => 9],
//            [['capacity'], 'exist', 'skipOnError' => true, 'targetClass' => TblCapacity::className(), 'targetAttribute' => ['capacity' => 'id']],
//            [['manufacturer_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblManufacturer::className(), 'targetAttribute' => ['manufacturer_code' => 'id']],
//            [['subcenter_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['subcenter_code' => 'sub_center_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'extra_tank_capacity' => Yii::t('app', 'Extra Tank Capacity'),
            'has_extra_tank' => Yii::t('app', 'Has Extra Tank'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'installment_allocation_date' => Yii::t('app', 'Installment Allocation Date'),
            'is_active' => Yii::t('app', 'Is Active'),
            'model' => Yii::t('app', 'Model'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'remarks' => Yii::t('app', 'Remarks'),
            'serial_no' => Yii::t('app', 'Serial No'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'capacity' => Yii::t('app', 'Capacity'),
            'created_by' => Yii::t('app', 'Created By'),
            'manufacturer_code' => Yii::t('app', 'Manufacturer Code'),
            'subcenter_code' => Yii::t('app', 'Subcenter Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapacity0()
    {
        return $this->hasOne(TblCapacity::className(), ['id' => 'capacity']);
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
        return $this->hasOne(User::className());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturerCode()
    {
        return $this->hasOne(TblManufacturer::className(), ['id' => 'manufacturer_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubcenterCode()
    {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'subcenter_code']);
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
     * @return TblSubcenterBmcHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblSubcenterBmcHistoryQuery(get_called_class());
    }
}
