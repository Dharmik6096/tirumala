<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_dcs_location_detail".
 *
 * @property string $dcs_location_detail_code
 * @property string $date_time_of_location
 * @property string $latitude
 * @property string $longitude
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
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
 * @property string $received_timestamp
 */
class TblDcsLocationDetail extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dcs_location_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dcs_location_detail_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'originating_org_code', 'originating_org_type', 'date_time_of_location', 'created_at', 'updated_at', 'received_timestamp', 'originating_type', 'latitude', 'longitude', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_by', 'updated_by'], 'safe'],
                [['union_code'], 'required', 'on' => ['androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_location_detail_code' => Yii::t('app', 'Dcs Location Detail Code'),
            'date_time_of_location' => Yii::t('app', 'Date Time Of Location'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'Address'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'received_timestamp' => Yii::t('app', 'Received Timestamp'),
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

}
