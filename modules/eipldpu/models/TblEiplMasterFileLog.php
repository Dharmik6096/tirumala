<?php

namespace app\modules\eipldpu\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_master_file_log".
 *
 * @property integer $file_log_code
 * @property string $process_type
 * @property string $data_type
 * @property integer $dpu_type
 * @property string $file_type
 * @property string $file_path
 * @property string $file_name
 * @property string $file_name_download
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $ref_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblEiplMasterFileLog extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_master_file_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['process_type', 'data_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code'], 'required'],
            [['process_type', 'data_type', 'file_type', 'file_path', 'file_name', 'file_name_download', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'ref_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['dpu_type', 'originating_type'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'file_log_code' => Yii::t('app', 'File Log Code'),
            'process_type' => Yii::t('app', 'Process Type'),
            'data_type' => Yii::t('app', 'Data Type'),
            'dpu_type' => Yii::t('app', 'Dpu Type'),
            'file_type' => Yii::t('app', 'File Type'),
            'file_path' => Yii::t('app', 'File Path'),
            'file_name' => Yii::t('app', 'File Name'),
            'file_name_download' => Yii::t('app', 'File Name Download'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'PLANT'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
