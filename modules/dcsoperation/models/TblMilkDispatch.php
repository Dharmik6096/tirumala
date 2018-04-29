<?php

namespace app\modules\dcsoperation\models;

use Yii;
use webvimark\modules\UserManagement\models\User;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblUnions;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\globalmaster\models\TblAnimalType;

/**
 * This is the model class for table "tbl_milk_dispatch".
 *
 * @property string $milk_dispatch_code
 * @property double $acidity
 * @property double $avg_clr
 * @property double $avg_fat
 * @property double $avg_snf
 * @property string $challan_no
 * @property string $chamber_no
 * @property string $created_at
 * @property string $deleted_at
 * @property double $density
 * @property string $destination_code
 * @property double $dip_stick_reading_closing
 * @property double $dip_stick_reading_opening
 * @property double $dispatch_qty
 * @property double $freezing_point
 * @property string $from_date
 * @property string $from_shift
 * @property string $head_load_kms
 * @property integer $is_active
 * @property integer $is_delete
 * @property double $lactose
 * @property integer $non_default_dispatch
 * @property integer $nos_of_can
 * @property double $protein
 * @property string $route_no
 * @property string $seal_no
 * @property double $temp
 * @property integer $to_bmc_plant
 * @property string $to_date
 * @property string $to_shift
 * @property string $updated_at
 * @property string $vehicle_in_time
 * @property string $vehicle_no
 * @property string $vehicle_out_time
 * @property double $water
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property integer $milk_quality_type
 * @property integer $milk_type
 * @property string $sub_center_code
 * @property string $union_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblMilkQualityType $milkQualityType
 * @property TblAnimalType $milkType
 * @property TblSubCenter $subCenterCode
 * @property TblUnions $unionCode
 * @property User $updatedBy
 */
class TblMilkDispatch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_milk_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['milk_dispatch_code'], 'required'],
            [['acidity', 'avg_clr', 'avg_fat', 'avg_snf', 'density', 'dip_stick_reading', 'dispatch_qty', 'freezing_point', 'lactose', 'protein', 'temp', 'water'], 'number'],
            [['created_at','dip_stick_reading_closing','head_load_kms','dip_stick_reading_opening', 'deleted_at', 'from_date', 'to_date', 'updated_at'], 'safe'],
            [['is_active', 'is_delete', 'non_default_dispatch', 'nos_of_can', 'to_bmc_plant', 'milk_quality_type', 'milk_type'], 'integer'],
            [['milk_dispatch_code', 'challan_no'], 'string', 'max' => 20],
            [['chamber_no', 'destination_code', 'route_no', 'seal_no', 'vehicle_in_time', 'vehicle_no', 'vehicle_out_time'], 'string', 'max' => 255],
            [['from_shift',  'to_shift'], 'string', 'max' => 1],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code', 'sub_center_code'], 'string', 'max' => 9],
            [['union_code'], 'string', 'max' => 3],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['milk_quality_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkQualityType::className(), 'targetAttribute' => ['milk_quality_type' => 'milk_quality_type_code']],
            [['milk_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type' => 'animal_type_code']],
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
            'milk_dispatch_code' => Yii::t('app', 'Milk Dispatch Code'),
            'acidity' => Yii::t('app', 'Acidity'),
            'avg_clr' => Yii::t('app', 'Avg Clr'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'chamber_no' => Yii::t('app', 'Chamber No'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'density' => Yii::t('app', 'Density'),
            'destination_code' => Yii::t('app', 'Destination'),
            'dip_stick_reading' => Yii::t('app', 'Dip Stick Reading'),
            'dispatch_qty' => Yii::t('app', 'Dispatch Qty'),
            'freezing_point' => Yii::t('app', 'Freezing Point'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'lactose' => Yii::t('app', 'Lactose'),
            'non_default_dispatch' => Yii::t('app', 'Non Default Dispatch'),
            'nos_of_can' => Yii::t('app', 'Nos Of Can'),
            'protein' => Yii::t('app', 'Protein'),
            'route_no' => Yii::t('app', 'Route No'),
            'seal_no' => Yii::t('app', 'Seal No'),
            'temp' => Yii::t('app', 'Temp'),
            'to_bmc_plant' => Yii::t('app', 'To Bmc Plant'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'vehicle_in_time' => Yii::t('app', 'Vehicle In Time'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'vehicle_out_time' => Yii::t('app', 'Vehicle Out Time'),
            'water' => Yii::t('app', 'Water'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'milk_quality_type' => Yii::t('app', 'Milk Quality Type'),
            'milk_type' => Yii::t('app', 'Milk Type'),
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
    public function getMilkQualityType()
    {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type']);
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
     * @return TblMilkDispatchQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMilkDispatchQuery(get_called_class());
    }
    
    public function getBmcPlant(){
        
        switch ($this->to_bmc_plant){
            case 0;
                return 'BMC';
            case 1;
                return 'MMC';
            case 2;
                return 'Dairy Plant';    
        }
    }
    
    public function bmcArray(){
        return [0=>'BMC',1=>'MMC',2=> 'Dairy Plant'];
    }
}
