<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_milk_dispatch_txn_history".
 *
 * @property integer $id
 * @property string $dcs_milk_dispatch_txn_code
 * @property string $dcs_milk_dispatch_code
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property integer $nos_of_can
 * @property string $dispatch_qty
 * @property integer $qty_mode
 * @property string $converted_qty
 * @property integer $converted_qty_mode
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $avg_clr
 * @property string $water
 * @property string $temperature
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
 * @property string $total_amount
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblDcsMilkDispatchTxnHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_milk_dispatch_txn_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_milk_dispatch_txn_code', 'dcs_milk_dispatch_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'history_created_by', 'operation_type'], 'safe'],
            [['milk_quality_type_code', 'milk_type_code', 'nos_of_can', 'qty_mode', 'converted_qty_mode', 'originating_type'], 'safe'],
            [['dispatch_qty', 'converted_qty', 'avg_fat', 'avg_snf', 'avg_clr', 'water', 'temperature', 'total_amount'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dcs_milk_dispatch_txn_code' => Yii::t('app', 'Dcs Milk Dispatch Txn Code'),
            'dcs_milk_dispatch_code' => Yii::t('app', 'Dcs Milk Dispatch Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'nos_of_can' => Yii::t('app', 'Nos Of Can'),
            'dispatch_qty' => Yii::t('app', 'Dispatch Qty'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'avg_clr' => Yii::t('app', 'Avg Clr'),
            'water' => Yii::t('app', 'Water'),
            'temperature' => Yii::t('app', 'Temperature'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
