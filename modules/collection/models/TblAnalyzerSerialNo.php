<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_analyzer_serial_no".
 *
 * @property string $analyzer_serial_no_code
 * @property string $date_time_of_serial_no
 * @property integer $shift_code
 * @property string $serial_no
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
 * @property string $data_inserted_from
 * @property integer $txfarmer_id
 * @property string $received_timestamp
 * @property integer $milk_analyser_type_code
 */
class TblAnalyzerSerialNo extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_analyzer_serial_no';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['analyzer_serial_no_code'], 'required'],
                [['date_time_of_serial_no', 'created_at', 'updated_at', 'received_timestamp'], 'safe'],
                [['shift_code', 'originating_type', 'txfarmer_id', 'milk_analyser_type_code'], 'safe'],
                [['analyzer_serial_no_code', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['serial_no'], 'safe'],
                [['union_code'], 'safe'],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['data_inserted_from'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'analyzer_serial_no_code' => 'Analyzer Serial No Code',
            'date_time_of_serial_no' => 'Date Time Of Serial No',
            'shift_code' => 'Shift Code',
            'serial_no' => 'Serial No',
            'union_code' => 'Union Code',
            'plant_code' => 'Plant Code',
            'mcc_plant_code' => 'Mcc Plant Code',
            'bmc_code' => 'Bmc Code',
            'dcs_code' => 'Dcs Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'originating_type' => 'Originating Type',
            'x_col1' => 'X Col1',
            'x_col2' => 'X Col2',
            'x_col3' => 'X Col3',
            'x_col4' => 'X Col4',
            'x_col5' => 'X Col5',
            'data_inserted_from' => 'Data Inserted From',
            'txfarmer_id' => 'Txfarmer ID',
            'received_timestamp' => 'Received Timestamp',
            'milk_analyser_type_code' => 'Milk Analyser Type Code',
        ];
    }

}
