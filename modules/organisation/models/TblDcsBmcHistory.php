<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_bmc_history".
 *
 * @property integer $id
 * @property string $bmc_code
 * @property string $bmc_type_code
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
 * @property string $bmc_milk_type
 * @property integer $capacity
 * @property string $created_by
 * @property integer $manufacturer_code
 * @property string $updated_by
 *
 * @property TblAnimalType $bmcMilkType
 * @property TblCapacity $capacity0
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblManufacturer $manufacturerCode
 * @property User $updatedBy
 */
class TblDcsBmcHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_at', 'created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at', 'is_active', 'is_mcc', 'valid_from', 'sap_vendor_code'], 'safe'],
                [['model', 'bmc_code', 'mcc_code', 'bmc_type_code', 'extra_tank_capacity', 'bmc_milk_type', 'capacity', 'manufacturer_code'], 'safe'],
                [['state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'bmc_name', 'union_code', 'local_name'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'plant_code', 'is_weight_manual', 'is_quality_manual'], 'safe'],
                [['bmc_code_ex', 'ref_code', 'vendor_code', 'auto_code', 'rate_calculate_on_merge', 'billing_type'], 'safe'],
                [['data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime', 'history_created_by'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'bmc_type_code' => Yii::t('app', 'Bmc Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'model' => Yii::t('app', 'Model'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'bmc_milk_type' => Yii::t('app', 'Bmc Milk Type'),
            'capacity' => Yii::t('app', 'Capacity'),
            'created_by' => Yii::t('app', 'Created By'),
            'subcenter_code' => Yii::t('app', 'subcenter_code'),
            'manufacturer_code' => Yii::t('app', 'Manufacturer Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBmcMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'bmc_milk_type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCapacity0() {
        return $this->hasOne(TblCapacity::className(), ['id' => 'capacity']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy() {
        return $this->hasOne(User::className());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturerCode() {
        return $this->hasOne(TblManufacturer::className(), ['id' => 'manufacturer_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblDcsBmcHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblDcsBmcHistoryQuery(get_called_class());
    }

}
