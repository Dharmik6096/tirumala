<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_payment_history".
 *
 * @property integer $id
 * @property integer $vehicle_payment_code
 * @property integer $transporter_payment_code
 * @property string $transporter_code
 * @property string $bmc_code
 * @property string $union_code
 * @property string $vehicle_code
 * @property string $route_code
 * @property string $coll_qty
 * @property string $coll_kg_fat
 * @property string $coll_kg_snf
 * @property string $disp_qty
 * @property string $disp_kg_fat
 * @property string $disp_kg_snf
 * @property string $rec_qty
 * @property string $rec_kg_fat
 * @property string $rec_kg_snf
 * @property string $cd_qty_diff
 * @property string $cd_kg_fat_diff
 * @property string $cd_kg_snf_diff
 * @property string $rd_qty_diff
 * @property string $rd_kg_fat_diff
 * @property string $rd_kg_snf_diff
 * @property integer $no_of_days
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $final_amount
 * @property string $from_date
 * @property string $to_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $status
 */
class TblVehiclePaymentHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_payment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_payment_code', 'transporter_payment_code', 'no_of_days'], 'integer'],
            [['transporter_code', 'bmc_code', 'union_code', 'vehicle_code', 'route_code', 'created_by', 'updated_by', 'operation_type', 'status'], 'string'],
            [['coll_qty', 'coll_kg_fat', 'coll_kg_snf', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'cd_qty_diff', 'cd_kg_fat_diff', 'cd_kg_snf_diff', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'total_amount', 'total_deduction', 'final_amount'], 'number'],
            [['from_date', 'to_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'vehicle_payment_code' => Yii::t('app', 'Vehicle Payment Code'),
            'transporter_payment_code' => Yii::t('app', 'Transporter Payment Code'),
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'vehicle_code' => Yii::t('app', 'Vehicle Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'coll_qty' => Yii::t('app', 'Coll Qty'),
            'coll_kg_fat' => Yii::t('app', 'Coll Kg Fat'),
            'coll_kg_snf' => Yii::t('app', 'Coll Kg Snf'),
            'disp_qty' => Yii::t('app', 'Disp Qty'),
            'disp_kg_fat' => Yii::t('app', 'Disp Kg Fat'),
            'disp_kg_snf' => Yii::t('app', 'Disp Kg Snf'),
            'rec_qty' => Yii::t('app', 'Rec Qty'),
            'rec_kg_fat' => Yii::t('app', 'Rec Kg Fat'),
            'rec_kg_snf' => Yii::t('app', 'Rec Kg Snf'),
            'cd_qty_diff' => Yii::t('app', 'Cd Qty Diff'),
            'cd_kg_fat_diff' => Yii::t('app', 'Cd Kg Fat Diff'),
            'cd_kg_snf_diff' => Yii::t('app', 'Cd Kg Snf Diff'),
            'rd_qty_diff' => Yii::t('app', 'Rd Qty Diff'),
            'rd_kg_fat_diff' => Yii::t('app', 'Rd Kg Fat Diff'),
            'rd_kg_snf_diff' => Yii::t('app', 'Rd Kg Snf Diff'),
            'no_of_days' => Yii::t('app', 'No Of Days'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
