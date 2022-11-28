<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_reject_history".
 *
 * @property integer $id
 * @property integer $milk_reject_code
 * @property string $source_org_type
 * @property string $source_org_code
 * @property string $dest_org_type
 * @property string $dest_org_code
 * @property integer $shift_code
 * @property integer $milk_type_code
 * @property string $return_type
 * @property string $action_taken
 * @property string $remarks
 * @property string $fat
 * @property string $snf
 * @property integer $no_of_can
 * @property string $qty
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property integer $rejection_reason_code
 * @property integer $rejection_responsibility_code
 * @property string $date_time_of_collection
 * @property integer $sample_no
 * @property integer $qty_mode
 * @property string $device_id
 * @property string $version_no
 * @property integer $doc_no
 * @property string $vehicle_code
 * @property string $parsing_no
 * @property string $clr
 * @property string $water
 * @property string $route_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMilkRejectHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_reject_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_reject_code', 'shift_code', 'milk_type_code', 'no_of_can', 'originating_type', 'rejection_reason_code', 'rejection_responsibility_code', 'sample_no', 'qty_mode', 'doc_no'], 'safe'],
            [['source_org_type', 'source_org_code', 'dest_org_type', 'dest_org_code', 'return_type', 'action_taken', 'remarks', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'device_id', 'version_no', 'vehicle_code', 'parsing_no', 'route_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'operation_type', 'history_created_by'], 'safe'],
            [['fat', 'snf', 'qty', 'clr', 'water'], 'safe'],
            [['created_at', 'updated_at', 'date_time_of_collection', 'history_created_at', 'bmc_code', 'mcc_plant_code', 'plant_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'milk_reject_code' => Yii::t('app', 'Milk Reject Code'),
            'source_org_type' => Yii::t('app', 'Source Org Type'),
            'source_org_code' => Yii::t('app', 'Source Org Code'),
            'dest_org_type' => Yii::t('app', 'Dest Org Type'),
            'dest_org_code' => Yii::t('app', 'Dest Org Code'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'return_type' => Yii::t('app', 'Return Type'),
            'action_taken' => Yii::t('app', 'Action Taken'),
            'remarks' => Yii::t('app', 'Remarks'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'no_of_can' => Yii::t('app', 'No Of Can'),
            'qty' => Yii::t('app', 'Qty'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'rejection_reason_code' => Yii::t('app', 'Rejection Reason Code'),
            'rejection_responsibility_code' => Yii::t('app', 'Rejection Responsibility Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'device_id' => Yii::t('app', 'Device ID'),
            'version_no' => Yii::t('app', 'Version No'),
            'doc_no' => Yii::t('app', 'Doc No'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'parsing_no' => Yii::t('app', 'Parsing No'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'route_code' => Yii::t('app', 'Route Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
