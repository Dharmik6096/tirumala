<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsaccounting\models\TblTaxDetail;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_ledger_mapping_tax_detail".
 *
 * @property string $ledger_mapping_tax_detail_code
 * @property string $ledger_code
 * @property string $tax_detail_code
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
class TblLedgerMappingTaxDetail extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ledger_mapping_tax_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'purchase_ledger_code', 'tax_detail_code', 'originating_org_code', 'originating_org_type', 'originating_type', 'created_at', 'updated_at', 'ledger_mapping_tax_detail_code', 'sale_ledger_code'], 'safe'],
                [['ledger_mapping_tax_detail_code'], 'required'],
                [['sale_ledger_code', 'purchase_ledger_code'], 'validateLedgerMapping'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ledger_mapping_tax_detail_code' => Yii::t('app', 'Ledger Mapping Tax Detail Code'),
            'purchase_ledger_code' => Yii::t('app', 'Purchase Ledger'),
            'tax_detail_code' => Yii::t('app', 'Tax'),
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
            'sale_ledger_code' => Yii::t('app', 'Sale Ledger'),
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

    public function getPurchaseLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'purchase_ledger_code']);
    }

    public function getSaleLedgerCode() {
        return $this->hasOne(TblLedgers::className(), ['ledger_code' => 'sale_ledger_code']);
    }

    public function getTaxDetailCode() {
        return $this->hasOne(TblTaxDetail::className(), ['tax_detail_code' => 'tax_detail_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        if (!isset($this->is_sentbox) || $this->is_sentbox === TRUE) {
            $unions = TblUnions::findAll(['is_active' => 1]);
            foreach ($unions as $union) {
                $union_code = $union->union_code;
                $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', $union_code);
                $flag = (((isset($this->operation) && $this->operation == true)) ? $this->operation : ($insert)) ? 'INSERT' : 'UPDATE';
                $sentbox = new TblSentbox();
                $sentbox->source_org_id = $union_code;
                if (!($sentbox->setSentboxBatch($this, $flag, $sentboxArray))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function validateLedgerMapping($attribute, $params) {
        if (!empty($this->sale_ledger_code) && empty($this->purchase_ledger_code)) {
            $this->addError('purchase_ledger_code', 'Purchase Ledger is required when Sale Ledger is selected.');
        }

        if (!empty($this->purchase_ledger_code) && empty($this->sale_ledger_code)) {
            $this->addError('sale_ledger_code', 'Sale Ledger is required when Purchase Ledger is selected.');
        }
    }

}
