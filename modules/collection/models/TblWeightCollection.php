<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\configuration\models\TblMilkCollectionConfig;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\dcsoperation\models\TblMember;

/**
 * This is the model class for table "tbl_weight_collection".
 *
 * @property string $uuid
 * @property string $producer_flag
 * @property integer $sample_no
 * @property string $date_time_of_collection
 * @property string $shift_code
 * @property integer $milk_type_code
 * @property integer $milk_quality_type_code
 * @property integer $qty_mode
 * @property string $qty
 * @property integer $converted_qty_mode
 * @property string $converted_qty
 * @property double $cans
 * @property integer $rejected_can
 * @property string $rejected_qty
 * @property string $weight_datetime
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $device_id
 * @property string $version_no
 * @property string $vehicle_no
 * @property string $ws_code
 * @property integer $qty_auto
 * @property integer $doc_no
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $route_arrival_time
 * @property string $own_mcc_plant_code
 * @property string $own_bmc_code
 */
class TblWeightCollection extends \app\models\ChildModel {

    public $customer_name, $ref_code, $bmc_ref_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_weight_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['uuid'], 'required', 'except' => ['androidsync']],
                [['dcs_code', 'date_time_of_collection', 'sample_no', 'shift_code', 'milk_type_code', 'milk_quality_type_code', 'doc_no', 'qty'], 'required', 'on' => ['PortalCreate']],
                [['uuid', 'producer_flag', 'shift_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'created_by', 'updated_by', 'device_id', 'version_no', 'vehicle_no', 'ws_code', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'own_mcc_plant_code', 'own_bmc_code'], 'safe'],
                [['sample_no', 'milk_type_code', 'milk_quality_type_code', 'qty_mode', 'converted_qty_mode', 'rejected_can', 'qty_auto', 'doc_no', 'originating_type'], 'safe'],
                [['date_time_of_collection', 'weight_datetime', 'created_at', 'updated_at', 'route_arrival_time', 'customer_type', 'customer_code'], 'safe'],
                [['qty', 'converted_qty', 'cans', 'rejected_qty', 'bmc_silos_info_code', 'received_timestamp'], 'safe'],
                [['qty', 'converted_qty', 'cans', 'rejected_qty'], 'number', 'except' => ['androidsync']],
                [['qty'], 'double', 'min' => 0, 'max' => 99999, 'on' => ['edit_collection']],
                [['sample_no'], 'unique', 'targetAttribute' => ['date_time_of_collection', 'shift_code', 'mcc_plant_code', 'sample_no', 'doc_no'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['androidsync']],
                [['producer_flag'], 'default', 'value' => 'Y'],
                [['cans', 'rejected_can', 'rejected_qty'], 'default', 'value' => '0'],
                [['date_time_of_collection', 'shift_code'], 'backendData', 'except' => ['androidsync']],
                [['uuid'], 'validateBmcCode'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'uuid' => Yii::t('app', 'Uuid'),
            'producer_flag' => Yii::t('app', 'Producer Flag'),
            'sample_no' => Yii::t('app', 'Sample No.'),
            'date_time_of_collection' => Yii::t('app', 'Collection Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type Code'),
            'qty_mode' => Yii::t('app', 'Collection Mode'),
            'qty' => Yii::t('app', 'Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'cans' => Yii::t('app', 'Cans'),
            'rejected_can' => Yii::t('app', 'Rejected Can'),
            'rejected_qty' => Yii::t('app', 'Rejected Qty'),
            'weight_datetime' => Yii::t('app', 'Weight time'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'route_code' => Yii::t('app', 'Route'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'device_id' => Yii::t('app', 'Device ID'),
            'version_no' => Yii::t('app', 'Version No'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'qty_auto' => Yii::t('app', 'Qty Mode'),
            'doc_no' => Yii::t('app', 'Doc. No.'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'route_arrival_time' => Yii::t('app', 'Route Arrival Time'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
            'customer_type' => Yii::t('app', 'Type'),
            'customer_code' => Yii::t('app', 'Code'),
        ];
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getMilkQualityTypeCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function backendData() {
        $milkCollModel = new TblMilkCollectionConfig();
        $milkCollModel->union_code = $this->union_code;
        $milkCollData = $milkCollModel->getData();

        if (!empty($milkCollData) && $milkCollData->ltr_to_kg != 0 && !empty($milkCollData->ltr_to_kg)) {
            if ($milkCollData->collection_quantity_mode == 0) {
                $this->converted_qty = $this->qty * $milkCollData->ltr_to_kg;
                $this->qty_mode = 0;
                $this->converted_qty_mode = 1;
            } else {
                $this->converted_qty = $this->qty / $milkCollData->ltr_to_kg;
                $this->qty_mode = 1;
                $this->converted_qty_mode = 0;
            }
        }
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getDefaultBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'mcc_plant_code'])->andOnCondition(['tbl_bmc.is_mcc' => 1]);
    }

    public function validateBmcCode() {
        if (strtolower($this->customer_type) == 'DCS') {
            $routeCode = Yii::$app->general->getforeignkey($this->dcsCode, 'route_code');
        } else if (strtolower($this->customer_type) == 'member') {
            $routeCode = $this->route_code;
        } else {
            $routeCode = Yii::$app->general->getforeignkey($this->mainCustomerCode, 'route_code');
        }
        $this->route_code = !empty($routeCode) && $routeCode != 'N/A' ? $routeCode : $this->route_code;
        if (empty($this->bmc_code) || empty($this->own_bmc_code)) {
            $bmcCode = Yii::$app->general->getforeignkey($this->defaultBmcCode, 'bmc_code');
            $this->bmc_code = !empty($bmcCode) && $bmcCode != 'N/A' && empty($this->bmc_code) ? $bmcCode : $this->bmc_code;
            $this->own_bmc_code = !empty($bmcCode) && $bmcCode != 'N/A' && empty($this->own_bmc_code) ? $bmcCode : $this->own_bmc_code;
        }
    }

    public function getCustomerTypeFor() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'customer_code']);
    }

}
