<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_sentbox_desktop".
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
 * @property integer $data_post_status
 * @property string $picked_datetime
 * @property string $key_name
 * @property string $key_value
 * @property string $received_time
 * @property string $sync_time
 */
class TblSentboxDesktop extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_sentbox_desktop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uuid'], 'required'],
            [['uuid', 'sync_status', 'source_org_type', 'source_org_id', 'dest_org_type', 'dest_org_id', 'message_type', 'table_name', 'operation', 'json_text', 'error_log', 'originating_org_id', 'originating_org_type', 'source_device_mac', 'version_no', 'device_id', 'sequence_no'], 'safe'],
            [['posting_timestamp', 'sync_timestamp', 'error_timestamp', 'picked_datetime', 'data_post_status', 'received_time', 'sync_time', 'key_value', 'key_value'], 'safe'],
            [['data_post_status'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'key_name' => Yii::t('app', 'Key Name'),
            'key_value' => Yii::t('app', 'Key Value'),
            'received_time' => Yii::t('app', 'Received Time'),
            'sync_time' => Yii::t('app', 'Sync Time'),
        ];
    }

    public function getData() {
        $data = $this->find()
                ->where(['dest_org_id' => $this->dest_org_id, 'dest_org_type' => $this->dest_org_type, 'device_id' => $this->device_id])
                ->orderBy('posting_timestamp')
                ->limit(5)
                ->all();
        $response = [];
        foreach ($data as $key => $model) {
            $response[] = $this->jsonModel($model);
        }
        return $response;
    }

    public function getDataCount() {
        $data = $this->find()
                ->where(['dest_org_id' => $this->dest_org_id, 'dest_org_type' => $this->dest_org_type, 'device_id' => $this->device_id])
                ->count();
        return $data;
    }

    public function jsonModel($model) {
        $newModel = null;
        $scema = $model->getTableSchema();
        foreach ($model->attributes as $key => $a) {
            if ($scema->name == 'tbl_ledger' && $key == 'has_sub_ledger') {
            } else {
                $type = $scema->columns[$key]->type;
                if ($type == 'datetime') {
                    if ($a != '') {
                        $dt = new \DateTime($a);
                        $a = $dt->format('Y-m-d\TH:i:s.u');
                    }
                } else if ($type == 'timestamp') {
                    if ($a != '') {
                        $dt = new \DateTime($a);
                        $a = $dt->format('Y-m-d\TH:i:s.u');
                    }
                } else if ($type == 'boolean') {
                    $key = str_replace('is_', '', $key);
                    $a = ($a == 1) ? true : false;
                } elseif ($type == 'integer') {
                    $a = (int) $a;
                } elseif ($type == 'double') {
                    $a = (float) $a;
                } elseif ($type == 'bigint') {
                    $a = (int) $a;
                }
                $new_key = str_replace('_', ' ', $key);
                $new_key = ucwords($new_key);
                $new_key = str_replace(' ', '', $new_key);
                $new_key = lcfirst($new_key);
                $newModel[$new_key] = $a;
            }
        }
        return (object) $newModel;
    }
}
