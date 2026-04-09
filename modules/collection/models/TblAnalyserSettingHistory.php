<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_analyser_setting_history".
 *
 * @property integer $id
 * @property string $analyser_setting_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $current_setting
 * @property string $device_info
 * @property string $changed_setting
 * @property integer $status
 * @property string $serial_no
 * @property string $download_datetime
 * @property string $processed_datetime
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
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblAnalyserSettingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_analyser_setting_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['analyser_setting_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'current_setting', 'device_info', 'changed_setting', 'serial_no', 'created_by', 'updated_by', 'history_created_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'operation_type', 'status', 'originating_type', 'download_datetime', 'processed_datetime', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'analyser_setting_code' => Yii::t('app', 'Analyser Setting Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'current_setting' => Yii::t('app', 'Current Setting'),
            'device_info' => Yii::t('app', 'Device Info'),
            'changed_setting' => Yii::t('app', 'Changed Setting'),
            'status' => Yii::t('app', 'Status'),
            'serial_no' => Yii::t('app', 'Serial No'),
            'download_datetime' => Yii::t('app', 'Download Datetime'),
            'processed_datetime' => Yii::t('app', 'Processed Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
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
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
