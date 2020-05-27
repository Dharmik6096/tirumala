<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblDcsPurchaseRate;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_rate_recalculation".
 *
 * @property integer $rate_recalculation_code
 * @property integer $rate_code
 * @property string $rate_type
 * @property string $from_date
 * @property integer $from_shift
 * @property string $to_date
 * @property integer $to_shift
 * @property string $dcs_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $recalc_for
 * @property string $recalc_type
 */
class TblRateRecalculation extends \app\models\ChildModel {

    public $purchase_rate_code, $customer_name, $rate_desc, $sp_param;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_rate_recalculation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['module_type'], 'default', 'value' => 'collection'],
            [['rate_code'], 'required', 'message' => 'Please select at least one rate to update'],
            [['recalc_for'], 'required', 'on' => ['recalculation_search', 'recalculation_search_custom']],
            [['rate_code'], 'integer', 'except' => 'recalculation_search_custom'],
            [['from_shift', 'to_shift'], 'integer'],
            [['rate_type', 'union_code', 'created_by', 'updated_by', 'recalc_for', 'recalc_type'], 'string'],
            [['from_date', 'to_date', 'created_at', 'updated_at'], 'safe'],
            [['customer_type', 'customer_code', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
            [['purchase_rate_code', 'bmc_code', 'recalc_for', 'plant_code', 'mcc_plant_code', 'dcs_code', 'module_type'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'rate_recalculation_code' => Yii::t('app', 'Rate Recalculation Code'),
            'rate_code' => Yii::t('app', 'Rate Code'),
            'rate_type' => Yii::t('app', 'Rate Type'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'recalc_for' => Yii::t('app', 'Recalc For'),
            'recalc_type' => Yii::t('app', 'Method'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'customer_code' => Yii::t('app', 'Code'),
            'customer_type' => Yii::t('app', 'Type'),
        ];
    }

    public function getFromShiftId() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift']);
    }

    public function getToShiftId() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getRateDescCode() {
        return $this->hasOne(TblPurchaseRate::className(), ['purchase_rate_code' => 'rate_code']);
    }

    public function getDcsRateDescCode() {
        return $this->hasOne(TblDcsPurchaseRate::className(), ['purchase_rate_code' => 'rate_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['mcc_plant_code' => 'bmc_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

}
