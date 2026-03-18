<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_ledger_mapping_event".
 *
 * @property integer $ledger_mapping_event_code
 * @property integer $credit_sub_ledger
 * @property integer $debit_sub_ledger
 * @property string $credit_ledger_code
 * @property string $debit_ledger_code
 * @property integer $event_code
 * @property integer $event_code_default
 * @property integer $voucher_type_code
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
class TblLedgerMappingEvent extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ledger_mapping_event';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['credit_ledger_code', 'debit_ledger_code', 'originating_org_code', 'originating_org_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'ledger_mapping_event_code', 'credit_sub_ledger', 'debit_sub_ledger', 'event_code', 'event_code_default', 'voucher_type_code', 'originating_type', 'created_at', 'updated_at', 'voucher_narration', 'voucher_txn_credit_narration', 'voucher_txn_debit_narration', 'voucher_narration_local', 'voucher_txn_credit_narration_local', 'voucher_txn_debit_narration_local'], 'safe'],
                [['ledger_mapping_event_code'], 'required'],
                [['voucher_narration_local', 'voucher_txn_credit_narration_local', 'voucher_txn_debit_narration_local'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ledger_mapping_event_code' => Yii::t('app', 'Ledger Mapping Event Code'),
            'credit_sub_ledger' => Yii::t('app', 'Credit Sub Ledger'),
            'debit_sub_ledger' => Yii::t('app', 'Debit Sub Ledger'),
            'credit_ledger_code' => Yii::t('app', 'Credit Ledger'),
            'debit_ledger_code' => Yii::t('app', 'Debit Ledger'),
            'event_code' => Yii::t('app', 'Event'),
            'event_code_default' => Yii::t('app', 'Event Code Default'),
            'voucher_type_code' => Yii::t('app', 'Voucher Type'),
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
            'voucher_narration' => Yii::t('app', 'Voucher Narration'),
            'voucher_txn_credit_narration' => Yii::t('app', 'Voucher Txn Credit Narration'),
            'voucher_txn_debit_narration' => Yii::t('app', 'Voucher Txn Debit Narration'),
            'voucher_narration_local' => Yii::t('app', 'Voucher Narration Local'),
            'voucher_txn_credit_narration_local' => Yii::t('app', 'Voucher Txn Credit Narration Local'),
            'voucher_txn_debit_narration_local' => Yii::t('app', 'Voucher Txn Debit Narration Local'),
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

    public function getCreditLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'credit_ledger_code']);
    }

    public function getDebitLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'debit_ledger_code']);
    }

    public function getEventCode() {
        return $this->hasOne(TblEvent::className(), ['event_code' => 'event_code']);
    }

    public function getVoucherTypeCode() {
        return $this->hasOne(TblVoucherTypes::className(), ['voucher_type_code' => 'voucher_type_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $this->union_code);
        foreach ($sentboxArray as $sent) {
            $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

}
