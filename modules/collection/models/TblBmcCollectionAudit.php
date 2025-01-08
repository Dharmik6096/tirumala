<?php

namespace app\modules\collection\models;

use app\models\ChildModel;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use Yii;

/**
 * This is the model class for table "tbl_bmc_collection_audit".
 *
 * @property integer $bmc_collection_audit_code
 * @property integer $shift_code
 * @property integer $sample_no
 * @property string $qty
 * @property integer $qty_mode
 * @property string $converted_qty
 * @property integer $converted_qty_mode
 * @property integer $no_of_can
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property string $rtpl
 * @property string $amount
 * @property integer $qty_auto
 * @property integer $qlty_auto
 * @property string $dcs_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property integer $milk_type_code
 * @property integer $milk_quality_type_code
 * @property integer $milk_analyser_type_code
 * @property integer $ws_code
 * @property string $vehicle_no
 * @property string $route_arrival_time
 * @property string $own_bmc_code
 * @property string $own_mcc_plant_code
 * @property string $purchase_rate_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $adt_param
 * @property string $adt_value
 * @property integer $bmc_silos_info_code
 * @property string $antibiotic
 * @property string $tare_weight
 * @property string $gross_weight
 * @property string $scheme_rate
 * @property string $scheme_rate_code
 * @property string $actual_rate
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblBmcCollectionAudit extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bmc_collection_audit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shift_code','sample_no','qty','qty_mode','converted_qty','converted_qty_mode','no_of_can','fat','snf','clr','water','protein','density','lactose','rtpl','amount','qty_auto','qlty_auto','dcs_code','union_code','plant_code','mcc_plant_code','bmc_code','route_code','milk_type_code','milk_quality_type_code','milk_analyser_type_code','ws_code','vehicle_no','route_arrival_time','own_bmc_code','own_mcc_plant_code','purchase_rate_code','customer_type','customer_code','adt_param','adt_value','bmc_silos_info_code','antibiotic','tare_weight','gross_weight','scheme_rate','scheme_rate_code','actual_rate','is_active','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','x_col1','x_col2','x_col3','x_col4','x_col5'], 'safe'],
            [['union_code'], 'required', 'on' => ['androidsync']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bmc_collection_audit_code' => Yii::t('app', 'Bmc Collection Audit Code'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'qty' => Yii::t('app', 'Qty'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'no_of_can' => Yii::t('app', 'No Of Can'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'route_arrival_time' => Yii::t('app', 'Route Arrival Time'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'adt_param' => Yii::t('app', 'Adt Param'),
            'adt_value' => Yii::t('app', 'Adt Value'),
            'bmc_silos_info_code' => Yii::t('app', 'Bmc Silos Info Code'),
            'antibiotic' => Yii::t('app', 'Antibiotic'),
            'tare_weight' => Yii::t('app', 'Tare Weight'),
            'gross_weight' => Yii::t('app', 'Gross Weight'),
            'scheme_rate' => Yii::t('app', 'Scheme Rate'),
            'scheme_rate_code' => Yii::t('app', 'Scheme Rate Code'),
            'actual_rate' => Yii::t('app', 'Actual Rate'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }
}
