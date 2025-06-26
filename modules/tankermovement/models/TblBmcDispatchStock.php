<?php

namespace app\modules\tankermovement\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblBmcSilosInfo;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\tankermovement\models\TblQtyDiffType;

/**
 * This is the model class for table "tbl_bmc_dispatch_stock".
 *
 * @property string $bmc_dispatch_stock_code
 * @property string $transaction_date
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
 */
class TblBmcDispatchStock extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_dispatch_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_dispatch_stock_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'from_shift_code', 'to_date', 'to_shift_code', 'from_shift_code', 'from_date', 'qty_diff_type_code', 'milk_quality_type_code', 'milk_type_code', 'bmc_silos_info_code', 'fat', 'snf', 'opening_bal', 'purchase_qty', 'qty_diff', 'balance_qty'], 'required', 'except' => ['androidsync']],
            [['bmc_dispatch_stock_code', 'type', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'from_shift_code', 'from_date', 'from_date_tr'], 'safe'],
            [['transaction_date', 'to_date', 'created_at', 'updated_at', 'from_date', 'from_shift_code'], 'safe'],
            [['to_shift_code', 'qty_diff_type_code', 'milk_quality_type_code', 'milk_type_code', 'bmc_silos_info_code', 'originating_type'], 'integer'],
            [['opening_bal', 'closing_bal', 'purchase_qty', 'qty_diff', 'extra_qty', 'balance_qty', 'fat', 'snf', 'water'], 'number'],
            [['type'], 'default', 'value' => 'dispatch'],
            [['closing_bal', 'water'], 'default', 'value' => 0],
            [['transaction_date'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['type'], 'unique', 'targetAttribute' => ['to_date', 'bmc_code', 'bmc_silos_info_code', 'milk_type_code', 'milk_quality_type_code', 'type'], 'message' => Yii::t('app/validation', 'BMC Dispatch Stock has been already taken.'), 'on' => 'create'],
            [['to_date'], 'CheckDateValidation', 'skipOnError' => true, 'on' => 'create'],
            [['bmc_code'], 'ValidateData', 'skipOnError' => true, 'on' => 'create'],
            [['qty_diff'], 'number', 'min' => 0, 'message' => Yii::t('app/validation', '{attribute} must be greater than 0')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_dispatch_stock_code' => Yii::t('app', 'Bmc Dispatch Stock Code'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift_code' => Yii::t('app', 'To Shift'),
            'qty_diff_type_code' => Yii::t('app', 'Qty Diff Type'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'bmc_silos_info_code' => Yii::t('app', 'Bmc Silos Info'),
            'opening_bal' => Yii::t('app', 'Opening Bal'),
            'closing_bal' => Yii::t('app', 'Closing Bal'),
            'purchase_qty' => Yii::t('app', 'Purchase Qty'),
            'qty_diff' => Yii::t('app', 'Qty Diff'),
            'extra_qty' => Yii::t('app', 'Extra Qty'),
            'balance_qty' => Yii::t('app', 'Balance Qty'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'water' => Yii::t('app', 'Water'),
            'type' => Yii::t('app', 'Type'),
            'remarks' => Yii::t('app', 'Remarks'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
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
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift_code' => Yii::t('app', 'From Shift'),
        ];
    }

    public function getStockEntry() {
        return $this->find()->where(['bmc_code' => $this->bmc_code, 'to_date' => $this->to_date, 'milk_type_code' => $this->milk_type_code, 'bmc_silos_info_code' => $this->bmc_silos_info_code, 'milk_quality_type_code' => $this->milk_quality_type_code])->orderBy(['created_at' => SORT_DESC])->one();
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

    public function getFromShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift_code']);
    }

    public function getToShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift_code']);
    }

    public function getSilosInfoCode() {
        return $this->hasOne(TblBmcSilosInfo::className(), ['bmc_silos_info_code' => 'bmc_silos_info_code']);
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityType() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getQtyDiffType() {
        return $this->hasOne(TblQtyDiffType::className(), ['qty_diff_type_code' => 'qty_diff_type_code']);
    }

    public function CheckDateValidation() {
        if (empty($this->getErrors())) {
            $this->from_date = date('Y-m-d', strtotime($this->from_date)) . ' ' . \Yii::$app->general->getshift($this->from_shift_code) . '.000000';
            $this->to_date = date('Y-m-d', strtotime($this->to_date)) . ' ' . \Yii::$app->general->getshift($this->to_shift_code) . '.000000';

            if ($this->to_date < $this->from_date) {
                $this->addError('to_date', Yii::t('app/validation', 'To Date must not be less than from date.'));
                return FALSE;
            } else {
                $stock_date = TblBmcDispatchStock::find()->where(['bmc_code' => $this->bmc_code, 'milk_type_code' => $this->milk_type_code, 'milk_quality_type_code' => $this->milk_quality_type_code, 'bmc_silos_info_code' => $this->bmc_silos_info_code])->orderBy(['to_date' => SORT_DESC, 'created_at' => SORT_DESC])->one();
                if (!empty($stock_date)) {
                    $dispatch_date = ($stock_date->type == 'physical') ? date('Y-m-d H:i:s', strtotime('+12 hours', strtotime($stock_date->to_date))) . '.000000' : $stock_date->to_date;

                    if ($this->from_date < $dispatch_date) {
                        $this->addError('to_date', Yii::t('app/validation', 'Stock Punching already done for selected date.'));
                        return FALSE;
                    } else if ($this->from_date > $dispatch_date) {
                        $this->addError('to_date', Yii::t('app/validation', "From Date must be  '" . $dispatch_date . "'."));
                        return FALSE;
                    }
                }
            }
            return TRUE;
        }
    }

    public function ValidateData() {
        if (date('Y-m-d', strtotime($this->transaction_date)) < date('Y-m-d', strtotime($this->to_date))) {
            $this->addError('transaction_date', Yii::t('app/validation', 'Dispatch Date can not be less than To Date.'));
        }
    }

}
