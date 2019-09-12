<?php

namespace app\modules\syncutility\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_pendrive_import_export".
 *
 * @property integer $id
 * @property string $union_code
 * @property string $dcs_code
 * @property string $file_name
 * @property integer $no_of_records
 * @property resource $mode
 * @property string $created_at
 * @property string $created_by
 * @property integer $no_of_records_ignore
 * @property integer $download_counter
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
class TblPendriveImportExport extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_pendrive_import_export';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'dcs_code', 'file_name', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['no_of_records', 'no_of_records_ignore', 'download_counter', 'originating_type'], 'safe'],
            [['mode', 'created_by'], 'safe'],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'dest_org_type', 'dest_org_id', 'device_id', 'dcs_code'], 'required', 'except' => ['log']],
            [['created_at', 'updated_at', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dest_org_type', 'dest_org_id', 'device_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'union_code' => Yii::t('app', 'Union'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'file_name' => Yii::t('app', 'File Name'),
            'no_of_records' => Yii::t('app', 'No Of Records'),
            'mode' => Yii::t('app', 'Mode'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'no_of_records_ignore' => Yii::t('app', 'No Of Records Ignore'),
            'download_counter' => Yii::t('app', 'Download Counter'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblPendriveImportExportQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblPendriveImportExportQuery(get_called_class());
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
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

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

}
