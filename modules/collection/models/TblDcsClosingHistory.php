<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_closing_history".
 *
 * @property integer $id
 * @property string $dcs_closing_code
 * @property string $transaction_date
 * @property string $to_date
 * @property integer $to_shift_code
 * @property integer $milk_type_code
 * @property string $qty
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $protein
 * @property string $remarks
 * @property string $dcs_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblDcsClosingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_closing_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_closing_code'], 'safe'],
            [['transaction_date', 'to_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['to_shift_code', 'milk_type_code', 'originating_type'], 'safe'],
            [['qty', 'fat', 'snf', 'water', 'protein'], 'safe'],
            [['dcs_closing_code'], 'safe'],
            [['remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['dcs_code', 'bmc_code'], 'safe'],
            [['union_code'], 'safe'],
            [['plant_code', 'mcc_plant_code'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'dcs_closing_code' => 'Dcs Closing Code',
            'transaction_date' => 'Transaction Date',
            'to_date' => 'To Date',
            'to_shift_code' => 'To Shift Code',
            'milk_type_code' => 'Milk Type Code',
            'qty' => 'Qty',
            'fat' => 'Fat',
            'snf' => 'Snf',
            'water' => 'Water',
            'protein' => 'Protein',
            'remarks' => 'Remarks',
            'dcs_code' => 'Dcs Code',
            'union_code' => 'Union Code',
            'plant_code' => 'Plant Code',
            'mcc_plant_code' => 'Mcc Plant Code',
            'bmc_code' => 'Bmc Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'x_col1' => 'X Col1',
            'x_col2' => 'X Col2',
            'x_col3' => 'X Col3',
            'x_col4' => 'X Col4',
            'x_col5' => 'X Col5',
            'operation_type' => 'Operation Type',
            'history_created_at' => 'History Created At',
            'history_created_by' => 'History Created By',
        ];
    }

}
