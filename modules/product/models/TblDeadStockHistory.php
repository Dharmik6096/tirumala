<?php

namespace app\modules\product\models;

use Yii;
use yii\db\ActiveRecord;


class TblDeadStockHistory extends ActiveRecord
{
    public static function tableName()
    {
        return 'tbl_dead_stock_history';
    }

    public function rules()
    {
        return [
            [['dead_stock_code'], 'required', 'on' => ['androidsync']],
            [['id','dead_stock_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'name', 'name_local', 'ledger_account', 'qty', 'amount', 'purchase_date', 'transaction_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'is_active', 'history_created_at', 'history_created_by', 'operation_type'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'dead_stock_code' => Yii::t('app', 'Dead Stock Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'name' => Yii::t('app', 'Name'),
            'name_local' => Yii::t('app', 'Local Name'),
            'ledger_account' => Yii::t('app', 'Ledger Account'),
            'qty' => Yii::t('app', 'Quantity'),
            'amount' => Yii::t('app', 'Amount'),
            'purchase_date' => Yii::t('app', 'Purchase Date'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Status'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
