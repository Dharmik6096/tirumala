<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblVehicleType;

/**
 * This is the model class for table "tbl_weigh_bridge_data".
 *
 * @property string $uuid
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $date
 * @property string $time
 * @property integer $vehicle_type_code
 * @property string $vehicle_no
 * @property integer $type
 * @property string $location_code
 * @property string $location_detail
 * @property string $material_type_code
 * @property string $gross_weight
 * @property string $gross_weight_time
 * @property string $tare_weight
 * @property string $tare_weight_time
 * @property string $weight
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblWeighBridgeData extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_weigh_bridge_data';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid'], 'required', 'except' => 'androidsync'],
            [['date', 'time', 'gross_weight_time', 'tare_weight_time', 'created_at', 'updated_at'], 'safe'],
            [['vehicle_type_code', 'type', 'originating_type'], 'integer'],
            [['gross_weight', 'tare_weight', 'weight'], 'number'],
            [['uuid'], 'safe'],
            [['union_code'], 'safe'],
            [['plant_code', 'mcc_plant_code', 'location_code'], 'safe'],
            [['bmc_code', 'vehicle_no', 'material_type_code', 'originating_org_type', 'originating_org_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['location_detail'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'uuid' => Yii::t('app', 'Uuid'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'date' => Yii::t('app', 'Date'),
            'time' => Yii::t('app', 'Time'),
            'vehicle_type_code' => Yii::t('app', 'Vehicle Type'),
            'vehicle_no' => Yii::t('app', 'Vehicle No'),
            'type' => Yii::t('app', 'Type'),
            'location_code' => Yii::t('app', 'Location Code'),
            'location_detail' => Yii::t('app', 'Location Detail'),
            'material_type_code' => Yii::t('app', 'Material Type'),
            'gross_weight' => Yii::t('app', 'Gross Weight'),
            'gross_weight_time' => Yii::t('app', 'Gross Weight Time'),
            'tare_weight' => Yii::t('app', 'Tare Weight'),
            'tare_weight_time' => Yii::t('app', 'Tare Weight Time'),
            'weight' => Yii::t('app', 'Weight'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'material_type_code']);
    }

    public function getVehicleTypeCode() {
        return $this->hasOne(TblVehicleType::className(), ['vehicle_type_code' => 'vehicle_type_code']);
    }

}
