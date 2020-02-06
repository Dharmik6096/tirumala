<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_quality_collection".
 *
 * @property string $uuid
 * @property integer $sample_no
 * @property string $date_time_of_collection
 * @property string $shift_code
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $quality_datetime
 * @property integer $retest_count
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $device_id
 * @property string $version_no
 * @property integer $doc_no
 * @property integer $auto_flag
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property integer $qlty_auto
 * @property string $milk_analyser_type_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $own_mcc_plant_code
 * @property string $own_bmc_code
 */
class TblQualityCollection extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_quality_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid'], 'required', 'except' => ['androidsync']],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'date_time_of_collection', 'shift_code', 'sample_no', 'doc_no', 'fat', 'snf'], 'required', 'on' => ['PortalCreate']],
            [['uuid', 'shift_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'device_id', 'version_no', 'originating_org_code', 'originating_org_type', 'milk_analyser_type_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'own_mcc_plant_code', 'own_bmc_code'], 'safe'],
            [['sample_no', 'retest_count', 'doc_no', 'auto_flag', 'originating_type', 'qlty_auto'], 'safe'],
            [['date_time_of_collection', 'quality_datetime', 'created_at', 'updated_at'], 'safe'],
            [['fat', 'snf', 'clr', 'water'], 'safe'],
            [['fat', 'snf', 'clr', 'water'], 'number', 'except' => ['androidsync']],
            [['fat', 'snf', 'clr', 'water'], 'double', 'min' => 0, 'max' => 99, 'except' => ['androidsync']],
            [['fat', 'snf', 'clr', 'water', 'retest_count'], 'default', 'value' => 0],
            [['auto_flag'], 'default', 'value' => '1'],
            [['sample_no'], 'unique', 'targetAttribute' => ['date_time_of_collection', 'shift_code', 'mcc_plant_code', 'sample_no', 'doc_no'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'uuid' => Yii::t('app', 'Uuid'),
            'sample_no' => Yii::t('app', 'Sample No.'),
            'date_time_of_collection' => Yii::t('app', 'Collection Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'fat' => Yii::t('app', 'FAT(%)'),
            'snf' => Yii::t('app', 'SNF(%)'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'quality_datetime' => Yii::t('app', 'Quality Datetime'),
            'retest_count' => Yii::t('app', 'Retest Count'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'device_id' => Yii::t('app', 'Device ID'),
            'version_no' => Yii::t('app', 'Version No'),
            'doc_no' => Yii::t('app', 'Doc. No.'),
            'auto_flag' => Yii::t('app', 'Auto Flag'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'qlty_auto' => Yii::t('app', 'Qlty Mode'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'own_mcc_plant_code' => Yii::t('app', 'Own Mcc Plant Code'),
            'own_bmc_code' => Yii::t('app', 'Own Bmc Code'),
        ];
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
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

}
