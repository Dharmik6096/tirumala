<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_weight_rejection".
 *
 * @property string $uuid
 * @property integer $sample_no
 * @property string $date_time_of_collection
 * @property string $shift_code
 * @property integer $milk_type_code
 * @property integer $qty_mode
 * @property string $qty
 * @property integer $converted_qty_mode
 * @property string $converted_qty
 * @property string $cans
 * @property integer $return_type
 * @property string $device_id
 * @property string $version_no
 * @property integer $doc_no
 * @property string $vehicle_no
 * @property string $remarks
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $route_code
 * @property integer $rejection_reason_code
 * @property integer $rejection_responsibility_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblWeightRejection extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_weight_rejection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'required'],
            [['sample_no', 'milk_type_code', 'qty_mode', 'converted_qty_mode', 'return_type', 'doc_no', 'rejection_reason_code', 'rejection_responsibility_code'], 'integer'],
            [['uuid', 'sample_no', 'date_time_of_collection', 'shift_code', 'milk_type_code', 'qty_mode', 'qty', 'converted_qty_mode', 'converted_qty', 'cans', 'return_type', 'device_id', 'version_no', 'doc_no', 'vehicle_no', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'rejection_reason_code', 'rejection_responsibility_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
            [['qty', 'converted_qty', 'cans'], 'number'],
            [['uuid', 'version_no'], 'string', 'max' => 50],
            [['shift_code'], 'string', 'max' => 30],
            [['device_id'], 'string', 'max' => 500],
            [['vehicle_no'], 'string', 'max' => 20],
            [['remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['union_code'], 'string', 'max' => 3],
            [['plant_code', 'mcc_plant_code'], 'string', 'max' => 6],
            [['bmc_code', 'dcs_code', 'route_code'], 'string', 'max' => 12],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type', 'originating_type'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uuid' => Yii::t('app', 'Uuid'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'qty' => Yii::t('app', 'Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'cans' => Yii::t('app', 'Cans'),
            'return_type' => Yii::t('app', 'Return Type'),
            'device_id' => Yii::t('app', 'Device ID'),
            'version_no' => Yii::t('app', 'Version No'),
            'doc_no' => Yii::t('app', 'Doc No'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'remarks' => Yii::t('app', 'Remarks'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'rejection_reason_code' => Yii::t('app', 'Rejection Reason Code'),
            'rejection_responsibility_code' => Yii::t('app', 'Rejection Responsibility Code'),
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
}
