<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_vsp_transit_recovery".
 *
 * @property integer $vsp_transit_recovery_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $dcs_code
 * @property string $transaction_date
 * @property string $from_date
 * @property string $from_shift
 * @property string $to_date
 * @property string $to_shift
 * @property string $composite_qty
 * @property string $composite_fat
 * @property string $composite_snf
 * @property string $actual_qty
 * @property string $actual_fat
 * @property string $actual_snf
 * @property string $a_c_qty
 * @property string $a_c_fat
 * @property string $a_c_snf
 * @property string $composite_ts
 * @property string $actual_ts
 * @property string $ts_difference
 * @property string $ts_deduction_amount
 * @property integer $ts_loss_responsibility
 * @property string $qty_diff
 * @property string $qty_diff_type
 * @property integer $qty_diff_responsibility
 * @property string $shortage_recovery
 * @property string $total_recovery_incharge
 * @property string $total_recovery_transporter
 * @property string $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $flg_sentbox_entry
 * @property integer $originating_type
 * @property string $originating_org_code
 */
class TblVspTransitRecovery extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_transit_recovery';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transaction_date', 'from_date', 'to_date', 'created_at', 'updated_at'], 'safe'],
            [['composite_qty', 'composite_fat', 'composite_snf', 'actual_qty', 'actual_fat', 'actual_snf', 'a_c_qty', 'a_c_fat', 'a_c_snf', 'composite_ts', 'actual_ts', 'ts_difference', 'ts_deduction_amount', 'qty_diff', 'shortage_recovery', 'total_recovery_incharge', 'total_recovery_transporter'], 'number'],
            [['ts_loss_responsibility', 'qty_diff_responsibility', 'originating_type'], 'integer'],
            [['union_code', 'qty_diff_type'], 'string', 'max' => 3],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code'], 'string', 'max' => 12],
            [['from_shift', 'to_shift'], 'string', 'max' => 30],
            [['status'], 'string', 'max' => 20],
            [['created_by', 'updated_by', 'originating_org_code'], 'string', 'max' => 14],
            [['flg_sentbox_entry'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vsp_transit_recovery_code' => 'Vsp Transit Recovery Code',
            'union_code' => 'Union Code',
            'plant_code' => 'Plant Code',
            'mcc_plant_code' => 'Mcc Plant Code',
            'bmc_code' => 'Bmc Code',
            'route_code' => 'Route Code',
            'dcs_code' => 'Dcs Code',
            'transaction_date' => 'Transaction Date',
            'from_date' => 'From Date',
            'from_shift' => 'From Shift',
            'to_date' => 'To Date',
            'to_shift' => 'To Shift',
            'composite_qty' => 'Composite Qty',
            'composite_fat' => 'Composite Fat',
            'composite_snf' => 'Composite Snf',
            'actual_qty' => 'Actual Qty',
            'actual_fat' => 'Actual Fat',
            'actual_snf' => 'Actual Snf',
            'a_c_qty' => 'A C Qty',
            'a_c_fat' => 'A C Fat',
            'a_c_snf' => 'A C Snf',
            'composite_ts' => 'Composite Ts',
            'actual_ts' => 'Actual Ts',
            'ts_difference' => 'Ts Difference',
            'ts_deduction_amount' => 'Ts Deduction Amount',
            'ts_loss_responsibility' => 'Ts Loss Responsibility',
            'qty_diff' => 'Qty Diff',
            'qty_diff_type' => 'Qty Diff Type',
            'qty_diff_responsibility' => 'Qty Diff Responsibility',
            'shortage_recovery' => 'Shortage Recovery',
            'total_recovery_incharge' => 'Total Recovery Incharge',
            'total_recovery_transporter' => 'Total Recovery Transporter',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'flg_sentbox_entry' => 'Flg Sentbox Entry',
            'originating_type' => 'Originating Type',
            'originating_org_code' => 'Originating Org Code',
        ];
    }

   public function getExistData($data) {
        $status = 'Lock';
        return $this->find()
                        ->where('mcc_plant_code=\'' . $data->mcc_plant_code . '\' and status=\'' . $status . '\'')
                        ->andWhere('((\'' . date('Y-m-d', strtotime($data->date_time_of_collection)) . '\' between cast(from_date as date)  and cast(to_date as date)))')
                        ->count();
    }

}
