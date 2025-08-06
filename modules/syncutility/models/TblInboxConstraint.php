<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_inbox_constraint".
 *
 * @property string $uuid
 * @property string $sync_status
 * @property string $source_org_type
 * @property string $source_org_id
 * @property string $dest_org_type
 * @property string $dest_org_id
 * @property string $message_type
 * @property string $table_name
 * @property string $operation
 * @property string $json_text
 * @property string $error_log
 * @property integer $sequence_no
 * @property string $originating_org_id
 * @property string $originating_org_type
 * @property string $posting_timestamp
 * @property string $sync_timestamp
 * @property string $source_device_mac
 * @property string $version_no
 * @property string $device_id
 * @property string $processed_timestamp
 * @property string $error_timestamp
 */
class TblInboxConstraint extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_inbox_constraint';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['uuid'], 'safe'],
                [['uuid', 'sync_status', 'source_org_type', 'source_org_id', 'dest_org_type', 'dest_org_id', 'message_type', 'table_name', 'operation', 'json_text', 'error_log', 'originating_org_id', 'originating_org_type', 'source_device_mac', 'version_no', 'device_id'], 'safe'],
                [['sequence_no'], 'safe'],
                [['posting_timestamp', 'sync_timestamp', 'processed_timestamp', 'error_timestamp', 'data_post_status'], 'safe'],
                [['data_post_status'], 'default', 'value' => 0],
                [['picked_datetime', 'received_time', 'sync_time'], 'safe'],
                [['sync_time'], 'default', 'value' => date('Y-m-d H:i:s.u')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'uuid' => Yii::t('app', 'Uuid'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'source_org_type' => Yii::t('app', 'Source Org Type'),
            'source_org_id' => Yii::t('app', 'Source Org ID'),
            'dest_org_type' => Yii::t('app', 'Dest Org Type'),
            'dest_org_id' => Yii::t('app', 'Dest Org ID'),
            'message_type' => Yii::t('app', 'Message Type'),
            'table_name' => Yii::t('app', 'Table Name'),
            'operation' => Yii::t('app', 'Operation'),
            'json_text' => Yii::t('app', 'Json Text'),
            'error_log' => Yii::t('app', 'Error Log'),
            'sequence_no' => Yii::t('app', 'Sequence No'),
            'originating_org_id' => Yii::t('app', 'Originating Org ID'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'posting_timestamp' => Yii::t('app', 'Posting Timestamp'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'source_device_mac' => Yii::t('app', 'Source Device Mac'),
            'version_no' => Yii::t('app', 'Version No'),
            'device_id' => Yii::t('app', 'Device ID'),
            'processed_timestamp' => Yii::t('app', 'Processed Timestamp'),
            'error_timestamp' => Yii::t('app', 'Error Timestamp'),
        ];
    }

}
