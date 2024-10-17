<?php

namespace app\modules\vsp\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblDcs;
use app\modules\payment\models\TblPaymentCycleApplicability;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_transit_recovery".
 *
 * @property integer $transit_recovery_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $wef_date
 * @property integer $payment_cycle_code
 * @property string $from_date
 * @property string $to_date
 * @property string $milk_type_code
 * @property string $rate
 * @property string $plus_rate
 * @property string $ltr_conversion_rate
 * @property string $spilt_day
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblTransitRecovery extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transit_recovery';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['rate', 'milk_type_code', 'plus_rate', 'union_code', 'ltr_conversion_rate', 'spilt_day', 'wef_date', 'from_date', 'to_date', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'created_at', 'updated_at', 'payment_cycle_code', 'originating_type'], 'safe'],
                [['dcs_code', 'rate', 'milk_type_code', 'plus_rate', 'ltr_conversion_rate', 'spilt_day', 'wef_date', 'from_date', 'to_date'], 'required', 'on' => ['importCsv']],
                [['from_date', 'to_date', 'wef_date'], 'convertDateDot', 'on' => ['importCsv']],
                [['from_date', 'to_date', 'wef_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['from_date', 'to_date', 'wef_date'], 'convertDate', 'on' => ['importCsv']],
                [['to_date'], 'validateToDate', 'on' => ['importCsv']],
                [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code'], 'on' => ['importCsv']],
                [['dcs_code'], 'setImport', 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'transit_recovery_code' => Yii::t('app', 'Transit Recovery Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'rate' => Yii::t('app', 'Rate'),
            'plus_rate' => Yii::t('app', 'Plus Rate'),
            'ltr_conversion_rate' => Yii::t('app', 'Ltr Conversion Rate'),
            'spilt_day' => Yii::t('app', 'Spilt Day'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function validateToDate($attribute, $params) {
        if (!empty($this->to_date) && !empty($this->from_date) && ($this->from_date > $this->to_date)) {
            $this->addError($attribute, Yii::t('app/validation', 'To Date Must be Greater than From Date.'));
            return false;
        }
    }

    public function convertDateDot() {
        try {
            $this->from_date = Yii::$app->controls->view_date($this->from_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->from_date = '-';
        }
        try {
            $this->to_date = Yii::$app->controls->view_date($this->to_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->to_date = '-';
        }
        try {
            $this->wef_date = Yii::$app->controls->view_date($this->wef_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->wef_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->from_date = !empty($this->from_date) ? Yii::$app->controls->view_date($this->from_date, 'php:Y-m-d') : NULL;
            $this->to_date = !empty($this->to_date) ? Yii::$app->controls->view_date($this->to_date, 'php:Y-m-d') : NULL;
            $this->wef_date = !empty($this->wef_date) ? Yii::$app->controls->view_date($this->wef_date, 'php:Y-m-d') : NULL;
        }
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            $dcs = new TblDcs();
            $this->dcs_code = $dcs->getValidDcs($this->dcs_code);
            if (empty($this->dcs_code)) {
                $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
                return false;
            }
            $dcs_code = $this->dcsCode;
            $this->bmc_code = $dcs_code->bmc_code;
            $this->plant_code = $dcs_code->plant_code;
            $this->mcc_plant_code = $dcs_code->mcc_plant_code;
            $this->union_code = $dcs_code->union_code;

            $payment_applicability = new TblPaymentCycleApplicability();
            $payment_cycle_info = $payment_applicability->getPaymentCycle($this->from_date, $this->to_date, $this->bmc_code, 'DCS', 'BMC');
            if (empty($payment_cycle_info['payment_cycle_code'])) {
                $this->addError('payment_cycle_code', 'Payment Cycle is Not Available');
                return false;
            }
            $this->payment_cycle_code = $payment_cycle_info['payment_cycle_code'];
            $this->from_date = $this->from_date . ' ' . \Yii::$app->general->getshift($payment_cycle_info['from_shift']);
            $this->to_date = $this->to_date . ' ' . \Yii::$app->general->getshift($payment_cycle_info['to_shift']);
        }
    }

}
