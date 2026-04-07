<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_force_sync_request_history".
 *
 * @property integer $id
 * @property integer $force_sync_request_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $from_datetime
 * @property integer $from_shift
 * @property string $to_datetime
 * @property integer $to_shift
 * @property integer $is_download
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblForceSyncRequestHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_force_sync_request_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['force_sync_request_code', 'from_shift', 'to_shift', 'is_download', 'originating_type'], 'safe'],
                [['from_datetime', 'to_datetime', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['union_code'], 'safe'],
                [['plant_code', 'mcc_plant_code'], 'safe'],
                [['bmc_code', 'dcs_code'], 'safe'],
                [['created_by', 'updated_by', 'history_created_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'force_sync_request_code' => Yii::t('app', 'Force Sync Request Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'from_datetime' => Yii::t('app', 'From Datetime'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_datetime' => Yii::t('app', 'To Datetime'),
            'to_shift' => Yii::t('app', 'To Shift'),
            'is_download' => Yii::t('app', 'Is Download'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
