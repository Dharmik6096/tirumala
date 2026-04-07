<?php

namespace app\modules\syncutility\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "tbl_inbox".
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
 */
class TblInbox extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_inbox';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['uuid'], 'required'],
                [['uuid', 'sync_status', 'source_org_type', 'source_org_id', 'dest_org_type', 'dest_org_id', 'message_type', 'table_name', 'operation', 'json_text', 'error_log', 'originating_org_id', 'originating_org_type', 'source_device_mac', 'version_no'], 'safe'],
                [['sequence_no'], 'safe'],
                [['posting_timestamp', 'sync_timestamp', 'device_id', 'error_timestamp', 'data_post_status'], 'safe'],
                [['data_post_status'], 'default', 'value' => 0],
                [['picked_datetime', 'received_time', 'sync_time'], 'safe'],
                [['received_time'], 'default', 'value' => date('Y-m-d H:i:s.u')],
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
        ];
    }

    public function getSyncPriority() {
        return $this->hasOne(TblSyncPriority::className(), ['table_name' => 'table_name'])->andOnCondition(['tbl_sync_priority.sync_type' => 'tbl_inbox']);
    }

    public function getData() {
        /* return $this->find()
          ->joinWith(['syncPriority'])
          ->where(['or', ['tbl_inbox.error_log' => NULL], ['tbl_inbox.error_log' => '']])
          ->orderBy(['ISNULL(tbl_sync_priority.sequence_no,99)' => SORT_ASC, 'tbl_inbox.posting_timestamp' => SORT_ASC])
          ->limit(50)
          ->all(); */
        $datetime = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $query = $this->find()
                ->joinWith(['syncPriority'])
                //   ->where(['or', ['tbl_inbox.error_log' => NULL], ['tbl_inbox.error_log' => '']])
                ->where(['or', ['tbl_inbox.data_post_status' => NULL], ['tbl_inbox.data_post_status' => ''], ['tbl_inbox.data_post_status' => 0]])
                ->andWhere(['NOT IN', 'tbl_inbox.table_name', ['tbl_config_txn_result']])
                ->orderBy(['ISNULL(tbl_sync_priority.sequence_no,99)' => SORT_ASC, 'tbl_inbox.posting_timestamp' => SORT_ASC])
                ->limit(300);
        return $query->all();

        $pendingDataQuery = $this->find()
                ->joinWith(['syncPriority'])
                ->where(['and', ['IS NOT', 'tbl_inbox.error_log', NULL], ['<', 'tbl_inbox.error_timestamp', $datetime]])
                ->orderBy(['ISNULL(tbl_sync_priority.sequence_no,99)' => SORT_ASC, 'tbl_inbox.posting_timestamp' => SORT_ASC])
                ->limit(20);

        return $unionQuery = (new ActiveQuery(TblInbox::className()))->from([
                    'pending_data' => $query->union($pendingDataQuery, TRUE)
                ])->all();
    }

    public function generateSentBox(&$masterSave){
        $sentboxDesktop = new TblSentboxDesktop();
        $sentboxDesktop->setAttributes($this->attributes);
        $dest_org_id = $sentboxDesktop->source_org_id;
        $dest_org_type = $sentboxDesktop->source_org_type;
        $sentboxDesktop->source_org_id = $sentboxDesktop->dest_org_id;
        $sentboxDesktop->source_org_type = $sentboxDesktop->dest_org_type;
        $sentboxDesktop->dest_org_id = $dest_org_id;
        $sentboxDesktop->dest_org_type = $dest_org_type;
        $sentboxDesktop->device_id = 'AMUL'.$dest_org_id.'AMCS';
        $sentboxDesktop->posting_timestamp = $sentboxDesktop->received_time = date('Y-m-d H:i:s');
        $sentboxDesktop->picked_datetime = $sentboxDesktop->sync_timestamp = $sentboxDesktop->sync_time = $sentboxDesktop->error_log = NULL;
        $sentboxDesktop->data_post_status = 0;
        $masterSave[] = $sentboxDesktop;
        return;
    }

}
