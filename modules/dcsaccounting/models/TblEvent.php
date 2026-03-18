<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_event".
 *
 * @property integer $event_code
 * @property integer $ledger_credit
 * @property integer $ledger_debit
 * @property string $description
 * @property string $event_name
 * @property integer $event_code_default
 * @property integer $sub_ledger_credit
 * @property integer $sub_ledger_debit
 * @property integer $is_active
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
class TblEvent extends \app\models\ChildModel {

    public $credit_ledger_code, $debit_ledger_code, $credit_sub_ledger, $debit_sub_ledger, $voucher_type_code, $voucher_narration, $voucher_txn_credit_narration, $voucher_txn_debit_narration, $voucher_narration_local, $voucher_txn_credit_narration_local, $voucher_txn_debit_narration_local;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_event';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['event_code', 'ledger_credit', 'ledger_debit', 'event_code_default', 'sub_ledger_credit', 'sub_ledger_debit', 'is_active', 'originating_type', 'created_at', 'updated_at', 'description', 'event_name', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'credit_ledger_code', 'debit_ledger_code', 'credit_sub_ledger', 'debit_sub_ledger', 'voucher_type_code', 'voucher_narration', 'voucher_txn_credit_narration', 'voucher_txn_debit_narration', 'voucher_narration_local', 'voucher_txn_credit_narration_local', 'voucher_txn_debit_narration_local'], 'safe'],
                [['event_code'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'event_code' => Yii::t('app', 'Event Code'),
            'ledger_credit' => Yii::t('app', 'Ledger Credit'),
            'ledger_debit' => Yii::t('app', 'Ledger Debit'),
            'description' => Yii::t('app', 'Description'),
            'event_name' => Yii::t('app', 'Event Name'),
            'event_code_default' => Yii::t('app', 'Event Code Default'),
            'sub_ledger_credit' => Yii::t('app', 'Sub Ledger Credit'),
            'sub_ledger_debit' => Yii::t('app', 'Sub Ledger Debit'),
            'is_active' => Yii::t('app', 'Is Active'),
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
            'credit_sub_ledger' => Yii::t('app', 'Credit Sub ledger ?'),
            'debit_sub_ledger' => Yii::t('app', 'Debit Sub ledger ?'),
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

}
