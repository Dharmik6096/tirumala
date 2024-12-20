<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblShift;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;

/**
 * This is the model class for table "tbl_milk_collection_special_code".
 *
 * @property integer $milk_collection_special_code
 * @property string $member_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property integer $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $qty
 * @property string $rtpl
 * @property string $amount
 * @property integer $shift_code
 * @property string $date_time_of_collection
 * @property integer $sample_no
 * @property string $purchase_rate_code
 * @property string $clr
 * @property integer $qty_mode
 * @property string $qlty_time
 * @property string $qty_time
 * @property integer $milk_quality_type_code
 * @property integer $qlty_auto
 * @property integer $qty_auto
 * @property string $route_code
 * @property string $converted_qty
 * @property string $version_no
 * @property integer $converted_qty_mode
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property integer $dcs_payment_cycle_code
 * @property integer $milk_analyser_type_code
 * @property integer $ws_code
 * @property string $incentive
 * @property string $deduction
 * @property string $total_amount
 * @property string $own_bmc_code
 * @property string $own_mcc_plant_code
 * @property string $adt_param
 * @property string $adt_value
 * @property integer $is_sms_sent
 * @property string $antibiotic
 * @property integer $is_antibiotic
 * @property string $other_reading
 * @property string $received_timestamp
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMilkCollectionSpecialCode extends \app\models\ChildModel {

    public $org_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_special_code';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'purchase_rate_code', 'route_code', 'own_bmc_code', 'own_mcc_plant_code', 'milk_type_code', 'shift_code', 'sample_no', 'qty_mode', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'converted_qty_mode', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code', 'is_sms_sent', 'is_antibiotic', 'originating_type', 'fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'converted_qty', 'protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'adt_value', 'other_reading', 'member_code', 'version_no', 'antibiotic', 'x_col1', 'adt_param', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'date_time_of_collection', 'qlty_time', 'qty_time', 'received_timestamp', 'created_at', 'updated_at', 'org_type'], 'safe'],
                [['member_code'], 'required', 'except' => ['androidsync']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_collection_special_code' => Yii::t('app', 'Milk Collection Special Code'),
            'member_code' => Yii::t('app', 'Member'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'Society'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'rtpl' => Yii::t('app', 'RTPL'),
            'amount' => Yii::t('app', 'Amount'),
            'shift_code' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Collection Date'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate Code'),
            'clr' => Yii::t('app', 'CLR'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'route_code' => Yii::t('app', 'Route Code'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'version_no' => Yii::t('app', 'Version No'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'dcs_payment_cycle_code' => Yii::t('app', 'Dcs Payment Cycle Code'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'incentive' => Yii::t('app', 'Incentive'),
            'deduction' => Yii::t('app', 'Deduction'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'adt_param' => Yii::t('app', 'Adultration Param'),
            'adt_value' => Yii::t('app', 'Adultration Value'),
            'is_sms_sent' => Yii::t('app', 'Is Sms Sent'),
            'antibiotic' => Yii::t('app', 'Antibiotic'),
            'is_antibiotic' => Yii::t('app', 'Is Antibiotic'),
            'other_reading' => Yii::t('app', 'Other Reading'),
            'received_timestamp' => Yii::t('app', 'Received Timestamp'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Orignated Org Type'),
            'org_type' => Yii::t('app', 'Originated At'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getMilkQualityCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

}
