<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_dispatch_stock_history".
 *
 * @property string $id
 * @property string $bmc_dispatch_stock_code
 * @property string $transaction_date
 * @property string $from_date
 * @property integer $from_shift_code
 * @property string $to_date
 * @property integer $to_shift_code
 * @property integer $qty_diff_type_code
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property integer $bmc_silos_info_code
 * @property string $opening_bal
 * @property string $closing_bal
 * @property string $purchase_qty
 * @property string $qty_diff
 * @property string $extra_qty
 * @property string $balance_qty
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $type
 * @property string $remarks
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
class TblBmcDispatchStockHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_dispatch_stock_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['from_shift_code', 'to_shift_code', 'qty_diff_type_code', 'milk_quality_type_code', 'milk_type_code', 'bmc_silos_info_code', 'originating_type', 'operation_type', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'bmc_code', 'plant_code', 'mcc_plant_code', 'union_code', 'remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'type', 'history_created_by', 'id', 'bmc_dispatch_stock_code', 'opening_bal', 'closing_bal', 'purchase_qty', 'qty_diff', 'extra_qty', 'balance_qty', 'fat', 'snf', 'water', 'transaction_date', 'from_date', 'to_date', 'created_at', 'updated_at', 'history_created_at', 'from_date_tr'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'bmc_dispatch_stock_code' => Yii::t('app', 'Bmc Dispatch Stock Code'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift_code' => Yii::t('app', 'From Shift Code'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift_code' => Yii::t('app', 'To Shift Code'),
            'qty_diff_type_code' => Yii::t('app', 'Qty Diff Type Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'bmc_silos_info_code' => Yii::t('app', 'Bmc Silos Info Code'),
            'opening_bal' => Yii::t('app', 'Opening Bal'),
            'closing_bal' => Yii::t('app', 'Closing Bal'),
            'purchase_qty' => Yii::t('app', 'Purchase Qty'),
            'qty_diff' => Yii::t('app', 'Qty Diff'),
            'extra_qty' => Yii::t('app', 'Extra Qty'),
            'balance_qty' => Yii::t('app', 'Balance Qty'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'water' => Yii::t('app', 'Water'),
            'type' => Yii::t('app', 'Type'),
            'remarks' => Yii::t('app', 'Remarks'),
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
