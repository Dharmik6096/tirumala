<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_sentbox_clone".
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
 * @property string $error_timestamp
 * @property string $file_name
 */
class TblSentboxClone extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sentbox_clone';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['uuid'], 'required'],
                [['uuid', 'sync_status', 'source_org_type', 'source_org_id', 'dest_org_type', 'dest_org_id', 'message_type', 'table_name', 'operation', 'json_text', 'error_log', 'originating_org_id', 'originating_org_type', 'source_device_mac', 'version_no', 'device_id', 'file_name'], 'string'],
                [['sequence_no'], 'integer'],
                [['posting_timestamp', 'sync_timestamp', 'error_timestamp', 'data_post_status'], 'safe'],
                [['posting_timestamp'], 'default', 'value' => date('Y-m-d H:i:s')],
                [['data_post_status'], 'default', 'value' => 0],
                [['sync_timestamp'],'setDefaultData']
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
            'error_timestamp' => Yii::t('app', 'Error Timestamp'),
            'file_name' => Yii::t('app', 'File Name'),
        ];
    }

    public function setDefaultData() {
        $this->sync_timestamp = date('Y-m-d H:i:s');
        return true;
    }
}
