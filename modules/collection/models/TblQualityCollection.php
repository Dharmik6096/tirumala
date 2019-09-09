<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_quality_collection".
 *
 * @property string $uuid
 * @property integer $sample_no
 * @property string $collection_date
 * @property string $shift_code
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $quality_datetime
 * @property integer $retest_count
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_code
 * @property string $bmc_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $device_id
 * @property integer $doc_no
 * @property integer $auto_flag
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
            [['plant_code', 'mcc_code', 'bmc_code', 'collection_date', 'shift_code', 'sample_no', 'doc_no', 'fat', 'snf'], 'required', 'on' => ['PortalCreate']],
            [['uuid', 'shift_code', 'union_code', 'plant_code', 'mcc_code', 'bmc_code', 'created_by', 'updated_by', 'flg_sentbox_entry', 'sync_status'], 'safe'],
            [['sample_no', 'retest_count'], 'safe'],
            [['collection_date', 'quality_datetime', 'created_at', 'updated_at', 'sync_timestamp', 'device_id', 'auto_flag', 'doc_no'], 'safe'],
//            [['fat', 'snf', 'clr', 'water'], 'safe'],
            [['fat', 'snf', 'clr', 'water'], 'number'],
            [['fat', 'snf', 'clr', 'water'], 'double', 'min' => 0, 'max' => 99],
            [['fat', 'snf', 'clr', 'water', 'retest_count'], 'default', 'value' => 0],
            [['flg_sentbox_entry'], 'default', 'value' => 'Y'],
            [['sync_status'], 'default', 'value' => 'U'],
            [['auto_flag'], 'default', 'value' => '1'],
            [['sample_no'], 'unique', 'targetAttribute' => ['collection_date', 'shift_code', 'mcc_code', 'sample_no', 'doc_no'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'uuid' => Yii::t('app', 'Uuid'),
            'sample_no' => Yii::t('app', 'Sample No.'),
            'collection_date' => Yii::t('app', 'Date'),
            'shift_code' => Yii::t('app', 'Shift'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'clr' => Yii::t('app', 'CLR'),
            'water' => Yii::t('app', 'Water'),
            'quality_datetime' => Yii::t('app', 'Quality Datetime'),
            'retest_count' => Yii::t('app', 'Retest Count'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'doc_no' => Yii::t('app', 'Doc No.'),
        ];
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

}
