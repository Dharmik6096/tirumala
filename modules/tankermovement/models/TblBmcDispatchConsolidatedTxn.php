<?php

namespace app\modules\tankermovement\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_dispatch_consolidated_txn".
 *
 * @property string $bmc_dispatch_consolidated_txn_code
 * @property string $challan_no
 * @property string $bmc_milk_dispatch_txn_code
 * @property string $bmc_milk_dispatch_code
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property string $dispatch_qty
 * @property string $fat
 * @property string $snf
 * @property string $rtpl
 * @property string $amount
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
 */
class TblBmcDispatchConsolidatedTxn extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_dispatch_consolidated_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_dispatch_consolidated_txn_code', 'challan_no', 'bmc_milk_dispatch_txn_code', 'bmc_milk_dispatch_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['milk_quality_type_code', 'milk_type_code', 'is_rejected', 'originating_type'], 'safe'],
            [['dispatch_qty', 'fat', 'snf', 'rtpl', 'amount'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_dispatch_consolidated_txn_code' => Yii::t('app', 'Bmc Dispatch Consolidated Txn Code'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'bmc_milk_dispatch_txn_code' => Yii::t('app', 'Bmc Milk Dispatch Txn Code'),
            'bmc_milk_dispatch_code' => Yii::t('app', 'Bmc Milk Dispatch Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'dispatch_qty' => Yii::t('app', 'Dispatch Qty'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
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
        ];
    }

}
