<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_vsp_transit_recovery_history".
 *
 * @property integer $id
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
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblVspTransitRecoveryHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vsp_transit_recovery_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['vsp_transit_recovery_code', 'ts_loss_responsibility', 'qty_diff_responsibility', 'originating_type', 'transaction_date', 'from_date', 'to_date', 'created_at', 'updated_at', 'history_created_at', 'composite_qty', 'composite_fat', 'composite_snf', 'actual_qty', 'actual_fat', 'actual_snf', 'a_c_qty', 'a_c_fat', 'a_c_snf', 'composite_ts', 'actual_ts', 'ts_difference', 'ts_deduction_amount', 'qty_diff', 'shortage_recovery', 'total_recovery_incharge', 'total_recovery_transporter', 'composite_qty_in_kg', 'composite_kg_fat', 'composite_kg_snf', 'actual_qty_in_kg', 'actual_kg_fat', 'actual_kg_snf', 'kg_fat_difference', 'kg_snf_difference', 'kg_fat_recovery_amount', 'kg_snf_recovery_amount', 'type_of_shortage', 'deduction_kg_fat_difference', 'deduction_kg_snf_difference', 'union_code', 'qty_diff_type', 'plant_code', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'from_shift', 'to_shift', 'status', 'created_by', 'updated_by', 'originating_org_code', 'history_created_by', 'flg_sentbox_entry', 'operation_type', 'remarks', 'ts_deduction_for_incharge', 'ts_deduction_for_transporter', 'qty_recovery_for_incharge', 'qty_recovery_for_transporter'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'vsp_transit_recovery_code' => Yii::t('app', 'Vsp Transit Recovery Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'composite_qty' => Yii::t('app', 'Composite Qty'),
            'composite_fat' => Yii::t('app', 'Composite Fat'),
            'composite_snf' => Yii::t('app', 'Composite Snf'),
            'actual_qty' => Yii::t('app', 'Actual Qty'),
            'actual_fat' => Yii::t('app', 'Actual Fat'),
            'actual_snf' => Yii::t('app', 'Actual Snf'),
            'a_c_qty' => Yii::t('app', 'A C Qty'),
            'a_c_fat' => Yii::t('app', 'A C Fat'),
            'a_c_snf' => Yii::t('app', 'A C Snf'),
            'composite_ts' => Yii::t('app', 'Composite Ts'),
            'actual_ts' => Yii::t('app', 'Actual Ts'),
            'ts_difference' => Yii::t('app', 'Ts Difference'),
            'ts_deduction_amount' => Yii::t('app', 'Ts Deduction Amount'),
            'ts_loss_responsibility' => Yii::t('app', 'Ts Loss Responsibility'),
            'qty_diff' => Yii::t('app', 'Qty Diff'),
            'qty_diff_type' => Yii::t('app', 'Qty Diff Type'),
            'qty_diff_responsibility' => Yii::t('app', 'Qty Diff Responsibility'),
            'shortage_recovery' => Yii::t('app', 'Shortage Recovery'),
            'total_recovery_incharge' => Yii::t('app', 'Total Recovery Incharge'),
            'total_recovery_transporter' => Yii::t('app', 'Total Recovery Transporter'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
