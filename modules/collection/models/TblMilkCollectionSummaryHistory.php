<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_milk_collection_summary_history".
 *
 * @property integer $id
 * @property string $milk_collection_summary_code
 * @property string $date_time_of_collection
 * @property integer $shift_code
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $kg_fat
 * @property string $kg_snf
 * @property string $total_qty
 * @property string $avg_rate
 * @property string $total_amount
 * @property integer $sample_count
 * @property integer $auto_count
 * @property integer $manual_count
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
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
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblMilkCollectionSummaryHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_summary_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_collection_summary_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'history_created_by', 'operation_type', 'data_post_status', 'pick_datetime', 'response_datetime', 'resp_status', 'resp_desc'], 'safe'],
            [['date_time_of_collection', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['shift_code', 'sample_count', 'auto_count', 'manual_count', 'originating_type'], 'safe'],
            [['avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'total_qty', 'avg_rate', 'total_amount', 'received_timestamp','data_inserted_from'], 'safe'],
            [['data_post_status'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'milk_collection_summary_code' => Yii::t('app', 'Milk Collection Summary Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'kg_fat' => Yii::t('app', 'Kg Fat'),
            'kg_snf' => Yii::t('app', 'Kg Snf'),
            'total_qty' => Yii::t('app', 'Total Qty'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'sample_count' => Yii::t('app', 'Sample Count'),
            'auto_count' => Yii::t('app', 'Auto Count'),
            'manual_count' => Yii::t('app', 'Manual Count'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
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
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
