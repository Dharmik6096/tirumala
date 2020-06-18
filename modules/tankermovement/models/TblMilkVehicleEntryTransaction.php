<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_transaction".
 *
 * @property string $milk_vehicle_entry_transaction_code
 * @property string $milk_vehicle_entry_code
 * @property string $vehicle_entry_chamber_date
 * @property string $chamber_quantity
 * @property string $grn_no
 * @property string $chamber_no
 * @property string $challan_no
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property string $source_org_code
 * @property string $source_org_type
 * @property string $destination_code
 * @property string $destination_type
 * @property string $entry_type
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $density
 * @property string $protein
 * @property string $lactose
 * @property string $freezing_point
 * @property string $mbrt
 * @property string $temp
 * @property string $acidity
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMilkVehicleEntryTransaction extends \app\models\ChildModel {

    public $source, $destination;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['chamber_quantity', 'fat', 'snf', 'water', 'temp', 'milk_quality_type_code', 'milk_type_code', 'entry_type', 'chamber_no'], 'required'],
            [['milk_vehicle_entry_transaction_code', 'milk_vehicle_entry_code', 'grn_no', 'chamber_no', 'challan_no', 'source_org_code', 'source_org_type', 'destination_code', 'destination_type', 'entry_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
            [['vehicle_entry_chamber_date', 'created_at', 'updated_at'], 'safe'],
            [['chamber_quantity', 'fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'], 'number'],
            [['milk_quality_type_code', 'milk_type_code', 'originating_type'], 'integer'],
            [['challan_no', 'source_org_code', 'source_org_type', 'destination_type', 'destination_code'], 'required', 'when' => function ($model) {
                    return $model->entry_type == 'INDIVIDUAL';
                }, 'whenClient' => "function (attribute, value) {
              return $('#tblmilkvehicleentrytransaction-entry_type').val() == 'INDIVIDUAL';
          }"],
            [['entry_type'], 'validateCreate'],
            [['clr', 'protein', 'density', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'], 'default', 'value' => '0'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_vehicle_entry_transaction_code' => Yii::t('app', 'Milk Vehicle Entry Transaction Code'),
            'milk_vehicle_entry_code' => Yii::t('app', 'Milk Vehicle Entry Code'),
            'vehicle_entry_chamber_date' => Yii::t('app', 'Vehicle Entry Chamber Date'),
            'chamber_quantity' => Yii::t('app', 'Chamber Qty.'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'chamber_no' => Yii::t('app', 'Chamber No'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Qlty Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'source_org_code' => Yii::t('app', 'Source Code'),
            'source_org_type' => Yii::t('app', 'Source Type'),
            'destination_code' => Yii::t('app', 'Dest. Code'),
            'destination_type' => Yii::t('app', 'Dest. Type'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'fat' => Yii::t('app', 'FAT(%)'),
            'snf' => Yii::t('app', 'SNF(%)'),
            'clr' => Yii::t('app', 'CLR'),
            'water' => Yii::t('app', 'Water'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'freezing_point' => Yii::t('app', 'Freezing Point'),
            'mbrt' => Yii::t('app', 'Mbrt'),
            'temp' => Yii::t('app', 'Temp'),
            'acidity' => Yii::t('app', 'Acidity'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityType() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getCustomerCodeDest() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'destination_code']);
    }

    public function getBmcCodeDest() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'destination_code']);
    }

    public function getMccPlantCodeDest() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'destination_code']);
    }

    public function getPlantCodeDest() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'destination_code']);
    }

    public function getBmcCodeSource() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'source_org_code']);
    }

    public function validateCreate($attribute, $params) {
        if (!empty($this->milk_vehicle_entry_code) && $this->entry_type == 'CONSOLIDATED') {
            $count = $this->find()
                    ->where(['milk_vehicle_entry_code' => $this->milk_vehicle_entry_code, 'entry_type' => $this->entry_type, 'chamber_no' => $this->chamber_no, 'milk_type_code' => $this->milk_type_code, 'milk_quality_type_code' => $this->milk_quality_type_code])
                    ->andfilterWhere(['!=', 'milk_vehicle_entry_transaction_code', $this->milk_vehicle_entry_transaction_code])
                    ->count();
            if ($count > 0) {
                $this->addError('chamber_no', Yii::t('app/validation', 'Chamber Entry is already exist.'));
                return false;
            }
        } else if (!empty($this->milk_vehicle_entry_code) && $this->entry_type == 'INDIVIDUAL') {
            $count = $this->find()
                    ->where(['milk_vehicle_entry_code' => $this->milk_vehicle_entry_code, 'entry_type' => $this->entry_type, 'chamber_no' => $this->chamber_no, 'challan_no' => $this->challan_no])
                    ->andfilterWhere(['!=', 'milk_vehicle_entry_transaction_code', $this->milk_vehicle_entry_transaction_code])
                    ->count();
            if ($count > 0) {
                $this->addError('chamber_no', Yii::t('app/validation', 'Chamber Entry is already exist.'));
                return false;
            }
        }
    }

}
