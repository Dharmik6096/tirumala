<?php

namespace app\modules\dcsaccounting\models;

use Yii;

/**
 * This is the model class for table "tbl_voucher_history".
 *
 * @property integer $id
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $voucher_code
 * @property integer $auto_posted
 * @property integer $cancelled
 * @property string $bill_date
 * @property string $voucher_date
 * @property string $bill_no
 * @property string $remarks
 * @property integer $voucher_type_code
 * @property string $dock_code
 * @property string $financial_year_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblVoucherHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_voucher_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['history_created_at', 'operation_type', 'history_created_by', 'voucher_code', 'bill_no', 'remarks', 'dock_code', 'financial_year_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'auto_posted', 'cancelled', 'voucher_type_code', 'originating_type', 'bill_date', 'voucher_date', 'created_at', 'updated_at', 'process_reference', 'process_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'voucher_code' => Yii::t('app', 'Voucher Code'),
            'auto_posted' => Yii::t('app', 'Auto Posted'),
            'cancelled' => Yii::t('app', 'Cancelled'),
            'bill_date' => Yii::t('app', 'Bill Date'),
            'voucher_date' => Yii::t('app', 'Voucher Date'),
            'bill_no' => Yii::t('app', 'Bill No'),
            'remarks' => Yii::t('app', 'Remarks'),
            'voucher_type_code' => Yii::t('app', 'Voucher Type Code'),
            'dock_code' => Yii::t('app', 'Dock Code'),
            'financial_year_code' => Yii::t('app', 'Financial Year Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
