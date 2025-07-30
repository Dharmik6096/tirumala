<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_sample_milk_collection".
 *
 * @property integer $sample_milk_collection_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $route_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property integer $milk_type_code
 * @property integer $milk_quality_type_code
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property string $qty
 * @property integer $qty_mode
 * @property string $converted_qty
 * @property integer $converted_qty_mode
 * @property string $rtpl
 * @property string $amount
 * @property integer $purchase_rate_code
 * @property string $qlty_time
 * @property string $qty_time
 * @property integer $qlty_auto
 * @property integer $qty_auto
 * @property integer $milk_analyser_type_code
 * @property integer $ws_code
 * @property string $source_of_milk
 * @property string $remarks
 * @property string $version_no
 * @property string $own_bmc_code
 * @property string $own_mcc_plant_code
 * @property string $device_lat
 * @property string $device_long
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblSampleMilkCollection extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sample_milk_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'own_bmc_code', 'own_mcc_plant_code', 'device_lat', 'device_long', 'fat', 'snf', 'clr', 'water', 'protein', 'density', 'lactose', 'qty', 'converted_qty', 'rtpl', 'amount', 'shift_code', 'milk_type_code', 'milk_quality_type_code', 'qty_mode', 'converted_qty_mode', 'purchase_rate_code', 'qlty_auto', 'qty_auto', 'milk_analyser_type_code', 'ws_code', 'originating_type', 'date_time_of_collection', 'qlty_time', 'qty_time', 'created_at', 'updated_at', 'version_no', 'source_of_milk', 'remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['union_code'], 'required', 'on' => ['androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sample_milk_collection_code' => Yii::t('app', 'Sample Milk Collection Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'qty' => Yii::t('app', 'Qty'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'source_of_milk' => Yii::t('app', 'Source Of Milk'),
            'remarks' => Yii::t('app', 'Remarks'),
            'version_no' => Yii::t('app', 'Version No'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'device_lat' => Yii::t('app', 'Device Lat'),
            'device_long' => Yii::t('app', 'Device Long'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
