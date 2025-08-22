<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_milk_dispatch_txn_history".
 *
 * @property integer $id
 * @property string $bmc_milk_dispatch_txn_code
 * @property string $bmc_milk_dispatch_code
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property string $dispatch_qty
 * @property string $qty_diff
 * @property string $balance_qty
 * @property integer $qty_diff_type_code
 * @property integer $qty_mode
 * @property string $converted_qty
 * @property integer $converted_qty_mode
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property string $rtpl
 * @property string $amount
 * @property string $freezing_point
 * @property string $temperature
 * @property string $hsn_code
 * @property string $seal_no_top
 * @property string $seal_no_bottom
 * @property string $seal_no_broken
 * @property integer $bmc_silos_info_code
 * @property integer $chamber_no
 * @property string $dip_open
 * @property string $dip_close
 * @property string $dip_diff
 * @property integer $qty_auto
 * @property integer $qlty_auto
 * @property string $milk_analyser_type_code
 * @property string $ws_code
 * @property string $qty_time
 * @property string $qlty_time
 * @property string $adt_param
 * @property string $adt_value
 * @property integer $is_rejected
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
class TblBmcMilkDispatchTxnHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_milk_dispatch_txn_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_quality_type_code', 'milk_type_code', 'qty_diff_type_code', 'qty_mode', 'converted_qty_mode', 'bmc_silos_info_code', 'chamber_no', 'qty_auto', 'qlty_auto', 'is_rejected', 'originating_type', 'test_report_no', 'shift_of_milk'], 'safe'],
            [['dispatch_qty', 'qty_diff', 'balance_qty', 'converted_qty', 'fat', 'snf', 'clr', 'water', 'protein', 'density', 'lactose', 'rtpl', 'amount', 'freezing_point', 'temperature', 'dip_open', 'dip_close', 'dip_diff', 'adt_value'], 'safe'],
            [['qty_time', 'qlty_time', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['bmc_milk_dispatch_txn_code', 'bmc_milk_dispatch_code'], 'safe'],
            [['hsn_code', 'adt_param', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['seal_no_top', 'seal_no_bottom', 'seal_no_broken'], 'safe'],
            [['milk_analyser_type_code', 'ws_code'], 'safe'],
            [['union_code'], 'safe'],
            [['plant_code', 'mcc_plant_code'], 'safe'],
            [['bmc_code'], 'safe'],
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
            'id' => Yii::t('app', 'ID'),
            'bmc_milk_dispatch_txn_code' => Yii::t('app', 'Bmc Milk Dispatch Txn Code'),
            'bmc_milk_dispatch_code' => Yii::t('app', 'Bmc Milk Dispatch Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'dispatch_qty' => Yii::t('app', 'Dispatch Qty'),
            'qty_diff' => Yii::t('app', 'Qty Diff'),
            'balance_qty' => Yii::t('app', 'Balance Qty'),
            'qty_diff_type_code' => Yii::t('app', 'Qty Diff Type Code'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'freezing_point' => Yii::t('app', 'Freezing Point'),
            'temperature' => Yii::t('app', 'Temperature'),
            'hsn_code' => Yii::t('app', 'Hsn Code'),
            'seal_no_top' => Yii::t('app', 'Seal No Top'),
            'seal_no_bottom' => Yii::t('app', 'Seal No Bottom'),
            'seal_no_broken' => Yii::t('app', 'Seal No Broken'),
            'bmc_silos_info_code' => Yii::t('app', 'Bmc Silos Info Code'),
            'chamber_no' => Yii::t('app', 'Chamber No'),
            'dip_open' => Yii::t('app', 'Dip Open'),
            'dip_close' => Yii::t('app', 'Dip Close'),
            'dip_diff' => Yii::t('app', 'Dip Diff'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'adt_param' => Yii::t('app', 'Adt Param'),
            'adt_value' => Yii::t('app', 'Adt Value'),
            'is_rejected' => Yii::t('app', 'Is Rejected'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
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
