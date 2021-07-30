<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_weight_collection_history".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $producer_flag
 * @property integer $sample_no
 * @property string $date_time_of_collection
 * @property string $shift_code
 * @property integer $milk_type_code
 * @property integer $milk_quality_type_code
 * @property integer $qty_mode
 * @property string $qty
 * @property integer $converted_qty_mode
 * @property string $converted_qty
 * @property double $cans
 * @property integer $rejected_can
 * @property string $rejected_qty
 * @property string $weight_datetime
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $device_id
 * @property string $version_no
 * @property string $vehicle_no
 * @property string $ws_code
 * @property integer $qty_auto
 * @property integer $doc_no
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $route_arrival_time
 * @property string $own_mcc_plant_code
 * @property string $own_bmc_code
 * @property string $customer_type
 * @property string $customer_code
 * @property integer $bmc_silos_info_code
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblWeightCollectionHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_weight_collection_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'required'],
            [['sample_no', 'milk_type_code', 'milk_quality_type_code', 'qty_mode', 'converted_qty_mode', 'rejected_can', 'qty_auto', 'doc_no', 'originating_type', 'bmc_silos_info_code'], 'integer'],
            [['date_time_of_collection', 'weight_datetime', 'created_at', 'updated_at', 'route_arrival_time', 'history_created_at'], 'safe'],
            [['qty', 'converted_qty', 'cans', 'rejected_qty'], 'number'],
            [['uuid'], 'string', 'max' => 50],
            [['producer_flag', 'version_no', 'customer_type', 'customer_code'], 'string', 'max' => 20],
            [['shift_code'], 'string', 'max' => 30],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code', 'mcc_plant_code'], 'string', 'max' => 6],
            [['bmc_code', 'dcs_code', 'own_mcc_plant_code', 'own_bmc_code'], 'string', 'max' => 12],
            [['route_code', 'operation_type'], 'string', 'max' => 10],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['device_id'], 'string', 'max' => 500],
            [['vehicle_no', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['ws_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uuid' => Yii::t('app', 'Uuid'),
            'producer_flag' => Yii::t('app', 'Producer Flag'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'qty' => Yii::t('app', 'Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'cans' => Yii::t('app', 'Cans'),
            'rejected_can' => Yii::t('app', 'Rejected Can'),
            'rejected_qty' => Yii::t('app', 'Rejected Qty'),
            'weight_datetime' => Yii::t('app', 'Weight Datetime'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'device_id' => Yii::t('app', 'Device ID'),
            'version_no' => Yii::t('app', 'Version No'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'doc_no' => Yii::t('app', 'Doc No'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'route_arrival_time' => Yii::t('app', 'Route Arrival Time'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'bmc_silos_info_code' => Yii::t('app', 'Bmc Silos Info Code'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }
}
