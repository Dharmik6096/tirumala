<?php

namespace app\modules\product\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use webvimark\modules\UserManagement\models\User;
use Yii;


class TblDeadStock extends ChildModel
{
    public static function tableName()
    {
        return 'tbl_dead_stock';
    }

    public function rules()
    {
        return [
            [['dead_stock_code'], 'required', 'on' => ['androidsync']],
            [['dead_stock_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'name', 'name_local', 'ledger_account', 'qty', 'amount', 'purchase_date', 'transaction_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'is_active'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
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
        ];
    }

    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getUserCode()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
