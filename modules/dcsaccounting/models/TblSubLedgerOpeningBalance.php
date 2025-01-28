<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_sub_ledger_opening_balance".
 *
 * @property string $sub_ledger_opening_balance_code
 * @property string $ledger_code
 * @property string $sub_ledger_code
 * @property integer $credit_debit
 * @property string $balance
 * @property string $financial_year_code
 * @property integer $auto_manual
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
class TblSubLedgerOpeningBalance extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sub_ledger_opening_balance';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['credit_debit', 'auto_manual', 'originating_type', 'created_at', 'updated_at', 'sub_ledger_opening_balance_code', 'ledger_code', 'sub_ledger_code', 'originating_org_code', 'originating_org_type', 'financial_year_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['balance'], 'number'],
                [['sub_ledger_opening_balance_code'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'sub_ledger_opening_balance_code' => Yii::t('app', 'Sub Ledger Opening Balance Code'),
            'ledger_code' => Yii::t('app', 'Ledger'),
            'sub_ledger_code' => Yii::t('app', 'Sub Ledger'),
            'credit_debit' => Yii::t('app', 'Credit/Debit'),
            'balance' => Yii::t('app', 'Balance'),
            'financial_year_code' => Yii::t('app', 'Financial Year'),
            'auto_manual' => Yii::t('app', 'Is Auto?'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS Code'),
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

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'ledger_code']);
    }

    public function getSubLedgerCode() {
        return $this->hasOne(TblSubLedgers::className(), ['sub_ledger_code' => 'sub_ledger_code']);
    }

}
