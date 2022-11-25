<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_remuneration_summary_history".
 *
 * @property integer $id
 * @property integer $mcc_remuneration_summary_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property integer $calculate_milk_recovey
 * @property integer $calculate_other_head
 * @property string $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMccRemunerationSummaryHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_remuneration_summary_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_remuneration_summary_code', 'calculate_milk_recovey', 'calculate_other_head', 'originating_type'], 'safe'],
            [['from_datetime', 'to_datetime', 'from_shift', 'to_shift', 'status'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
            [['created_by', 'updated_by', 'created_at', 'updated_at', 'history_created_at', 'history_created_by', 'operation_type', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'mcc_remuneration_summary_code' => 'Mcc Remuneration Summary Code',
            'union_code' => 'Union Code',
            'plant_code' => 'Plant Code',
            'mcc_plant_code' => 'Mcc Plant Code',
            'bmc_code' => 'Bmc Code',
            'from_datetime' => 'From Datetime',
            'from_shift' => 'From Shift',
            'to_datetime' => 'To Datetime',
            'to_shift' => 'To Shift',
            'calculate_milk_recovey' => 'Calculate Milk Recovey',
            'calculate_other_head' => 'Calculate Other Head',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'originating_type' => 'Originating Type',
            'operation_type' => 'Operation Type',
            'history_created_at' => 'History Created At',
            'history_created_by' => 'History Created By',
        ];
    }

}
