<?php

namespace app\modules\collection\models;

use Yii;

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
            [['uuid'], 'required'],
            [['uuid', 'shift_code', 'union_code', 'plant_code', 'mcc_code', 'bmc_code', 'created_by', 'updated_by', 'flg_sentbox_entry', 'sync_status'], 'safe'],
            [['sample_no', 'retest_count'], 'safe'],
            [['collection_date', 'quality_datetime', 'created_at', 'updated_at', 'sync_timestamp', 'device_id', 'auto_flag', 'doc_no'], 'safe'],
            [['fat', 'snf', 'clr', 'water'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'uuid' => Yii::t('app', 'Uuid'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'collection_date' => Yii::t('app', 'Collection Date'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'clr' => Yii::t('app', 'Clr'),
            'water' => Yii::t('app', 'Water'),
            'quality_datetime' => Yii::t('app', 'Quality Datetime'),
            'retest_count' => Yii::t('app', 'Retest Count'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_code' => Yii::t('app', 'Mcc Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
        ];
    }

}
