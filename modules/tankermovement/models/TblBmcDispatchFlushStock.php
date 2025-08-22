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

/**
 * This is the model class for table "tbl_bmc_dispatch_flush_stock".
 *
 * @property integer $bmc_dispatch_flush_stock_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $transaction_date
 * @property integer $shift_code
 * @property integer $bmc_silos_info_code
 * @property integer $milk_type_code
 * @property integer $milk_quality_type_code
 * @property string $qty
 * @property string $remarks
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
class TblBmcDispatchFlushStock extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_dispatch_flush_stock';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'qty', 'shift_code', 'bmc_silos_info_code', 'milk_type_code', 'milk_quality_type_code', 'originating_type', 'transaction_date', 'created_at', 'updated_at', 'remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['qty'], 'double', 'min' => 0.01, 'message' => Yii::t('app/validation', '{attribute} must be greater than 0'), 'on' => ['create', 'update', 'importCsv']],
                [['transaction_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['transaction_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['transaction_date'], 'convertDate', 'on' => ['importCsv']],
                [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, TRUE);
                }, 'on' => ['importCsv']],
                [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
                [['shift_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'shift');
                }, 'on' => 'importCsv'],
                [['shift_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['shift_code' => 'id'], 'on' => ['importCsv']],
                [['bmc_code'], 'pastDateValidate', 'on' => ['importCsv']],
                [['bmc_code'], 'importData', 'skipOnError' => true, 'on' => ['importCsv']],
                [['bmc_silos_info_code'], 'validateSilo', 'skipOnError' => true, 'on' => ['importCsv']],
                [['bmc_code'], 'unique', 'targetAttribute' => ['bmc_code', 'transaction_date', 'shift_code', 'bmc_silos_info_code'], 'message' => Yii::t('app/validation', 'BMC Dispatch Flush Stock has been already taken.'), 'on' => ['create', 'update', 'importCsv']],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'qty', 'shift_code', 'bmc_silos_info_code', 'transaction_date', 'milk_type_code', 'milk_quality_type_code'], 'required', 'on' => ['create', 'update', 'importCsv'], 'except' => ['androidsync']],
                [['milk_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'milk_type_code');
                }, 'on' => 'importCsv'],
                [['milk_quality_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'milk_quality_type_code');
                }, 'on' => 'importCsv'],
                [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code'], 'on' => ['importCsv']],
                [['milk_quality_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkQualityType::className(), 'targetAttribute' => ['milk_quality_type_code' => 'milk_quality_type_code'], 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_dispatch_flush_stock_code' => Yii::t('app', 'Bmc Dispatch Flush Stock Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'transaction_date' => Yii::t('app', 'Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'bmc_silos_info_code' => Yii::t('app', 'Silo No.'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'qty' => Yii::t('app', 'Qty'),
            'remarks' => Yii::t('app', 'Remarks'),
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

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getSilosInfoCode() {
        return $this->hasOne(TblBmcSilosInfo::className(), ['bmc_silos_info_code' => 'bmc_silos_info_code'])->andOnCondition(['module_name' => 'BMC']);
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityType() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function convertDateDot() {
        try {
            $this->transaction_date = Yii::$app->controls->view_date($this->transaction_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->transaction_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->transaction_date = !empty($this->transaction_date) ? Yii::$app->controls->view_date($this->transaction_date, 'php:Y-m-d') : NULL;
        }
    }

    public function importData($attribute, $params) {
        if (empty($this->getErrors())) {
            $this->transaction_date = $this->transaction_date . ' ' . \Yii::$app->general->getshift($this->shift_code);
        }
        if ($this->isNewRecord) {
            $this->x_col1 = Yii::$app->general->getUuid();
        }
    }

    public function pastDateValidate($attribute, $params) {
        if (!empty($this->transaction_date) && ($this->transaction_date > date('Y-m-d'))) {
            $this->addError('transaction_date', Yii::t('app/validation', $this->getAttributeLabel('transaction_date') . ' Must be smaller than ' . date('d.m.Y')));
        }
    }

    public function validatesilo($attribute, $params) {
        $bmc_silo = TblBmcSilosInfo::find()->alias('s')->select('s.bmc_silos_info_code')
                ->innerJoin('tbl_bmc_chiller_info as c', 'c.bmc_code = s.module_code')
                ->where(['c.is_active' => 1, 's.is_active' => 1, 's.module_name' => 'BMC', 's.module_code' => $this->bmc_code])
                ->andWhere(['or', ['c.sap_vendor_code' => $this->bmc_silos_info_code], ['s.bmc_silos_info_code' => $this->bmc_silos_info_code]])
                ->one();
        if (empty($bmc_silo)) {
            $this->addError($attribute, Yii::t('app/validation', 'Silo No. is invalid.'));
            return false;
        } else {
            $this->bmc_silos_info_code = $bmc_silo->bmc_silos_info_code;
        }
    }

}
