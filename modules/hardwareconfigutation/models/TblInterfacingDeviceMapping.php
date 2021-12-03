<?php

namespace app\modules\hardwareconfigutation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\hardwareconfigutation\models\TblInterfacingDevice;

/**
 * This is the model class for table "tbl_interfacing_device_mapping".
 *
 * @property string $interfacing_device_mapping_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $weight_device_code
 * @property string $analyzer_device_code
 * @property string $printer_device_code
 * @property string $display_device_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblInterfacingDeviceMapping extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_interfacing_device_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['interfacing_device_mapping_code'], 'required'],
                [['created_at', 'updated_at'], 'safe'],
                [['originating_type'], 'integer'],
                [['interfacing_device_mapping_code', 'weight_device_code', 'analyzer_device_code', 'printer_device_code', 'display_device_code', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
                [['union_code'], 'string', 'max' => 3],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'string', 'max' => 12],
                [['created_by', 'updated_by'], 'string', 'max' => 14],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'interfacing_device_mapping_code' => Yii::t('app', 'Interfacing Device Mapping Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'weight_device_code' => Yii::t('app', 'Weight Device'),
            'analyzer_device_code' => Yii::t('app', 'Analyzer Device'),
            'printer_device_code' => Yii::t('app', 'Printer Device'),
            'display_device_code' => Yii::t('app', 'Display Device'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
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

    public function getWeightDeviceCode() {

        return $this->hasOne(TblInterfacingDevice::className(), ['interfacing_device_code' => 'weight_device_code']);
    }

    public function getAnalyzerDeviceCode() {
        return $this->hasOne(TblInterfacingDevice::className(), ['interfacing_device_code' => 'analyzer_device_code']);
    }

    public function getPrinterDeviceCode() {
        return $this->hasOne(TblInterfacingDevice::className(), ['interfacing_device_code' => 'printer_device_code']);
    }

    public function getDisplayDeviceCode() {
        return $this->hasOne(TblInterfacingDevice::className(), ['interfacing_device_code' => 'display_device_code']);
    }

}
