<?php

namespace app\modules\syncutility\models;

use Yii;
use app\modules\syncutility\models\TblAddressbook;
use yii\helpers\Json;
use app\modules\installation\models\TblAndroidInstallationDetails;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use app\models\GeneralModel;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_sentbox".
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
 */
class TblSentbox extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sentbox';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid'], 'required'],
            [['uuid', 'sync_status', 'source_org_type', 'source_org_id', 'dest_org_type', 'dest_org_id', 'message_type', 'table_name', 'operation', 'json_text', 'error_log', 'originating_org_id', 'originating_org_type', 'source_device_mac', 'version_no', 'device_id'], 'safe'],
            [['sequence_no'], 'safe'],
            [['posting_timestamp', 'sync_timestamp', 'error_timestamp', 'data_post_status'], 'safe'],
            [['data_post_status'], 'default', 'value' => 0]
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

    public function setSentbox($model, $operation, $count = 1) {

        $addressBook = $this->isAddressBook(!empty($this->table_name) ? $this->table_name : $model->tableName());
        foreach ($addressBook as $d) {
            $sentModel = new TblSentbox();
            $attribute = $this->attributes;
            $sentModel->setAttributes($attribute);
            switch ($d->destinations) {
                case 0:
                    $this->childEntry($model, $operation, $sentModel);
                    break;
                case 1:
                    if ($d->to_child == 1) {
                        $table = !empty($this->table_name) ? $this->table_name : $model->tableName();
                        if (in_array($table, ['tbl_user_organization_mapping', 'user'])) {
                            if ($model->entry_type == 1) {
                                $this->childEntry($model, $operation, $sentModel);
                            }
                        } else {
                            $this->childEntry($model, $operation, $sentModel);
                        }
                    }
                    if ($d->to_parent == 1 && $count == 1) {
                        $sentModel = new TblSentbox();
                        $attribute = $this->attributes;
                        $sentModel->setAttributes($attribute);
                        $this->parentEntry($model, $operation, $sentModel);
                    }
                    break;
                case 2:
                    $this->childEntry($model, $operation, $sentModel);
                    $sentModel = new TblSentbox();
                    $attribute = $this->attributes;
                    $sentModel->setAttributes($attribute);
                    if ($count == 1)
                        $this->parentEntry($model, $operation, $sentModel);
                    break;
                default:
                    break;
            }
        }
        return TRUE;
    }

    public function isAddressBook($table_name) {
        $model = new TblAddressbook();
        $data = $model->find()->select(['destinations', 'to_child', 'to_parent'])->where(
                        [
                            'table_name' => $table_name,
                            'source_org_type' => 'SELF',
                            'flag_entry' => '1',
                            'type' => '1'
                        ]
                )->all();
        return $data;
    }

    private function childEntry($model, $operation, $sentModel) {

        $destination = '';
        switch (Yii::$app->session->get('organizations_type')) {
            case 'NATIONAL':
                $destination = 'FEDERATION';
                break;
            case 'FEDERATION':
                $destination = 'UNION';
                break;
            case 'UNION':
                $destination = 'DCS';
                break;
        }
        $this->entry($model, $operation, $sentModel);
        $sentModel->dest_org_id = !empty($sentModel->dest_org_id) ? $sentModel->dest_org_id : '0';
        $sentModel->dest_org_type = !empty($sentModel->dest_org_type) ? $sentModel->dest_org_type : $destination;
        if ($sentModel->table_name == 'tbl_route_mapping') {
            $sentModel->table_name = 'tbl_route';
        } else if ($sentModel->table_name == 'tbl_route_mapping_sources') {
            $sentModel->table_name = 'tbl_route_mapping';
        }
        $data = $sentModel->attributes;
        $model = new TblAndroidInstallationDetails();
        $model_data = $model->getActiveDeviceData($sentModel->dest_org_id, $sentModel->dest_org_type, $sentModel->device_id);
        $save_model = [];
        if (!empty($model_data)) {
            foreach ($model_data as $device) {
                $sent_box_model = new TblSentbox();
                $sent_box_model->setAttributes($data);
                $sent_box_model->device_id = !empty($device->device_id) ? $device->device_id : '';
                $sent_box_model->originating_org_id = !empty($sent_box_model->originating_org_id) ? $sent_box_model->originating_org_id : '001';
                $sent_box_model->originating_org_type = !empty($sent_box_model->originating_org_type) ? $sent_box_model->originating_org_type : 'UNION';
                $sent_box_model->source_org_id = !empty($sent_box_model->source_org_id) ? $sent_box_model->source_org_id : '001';
                $sent_box_model->source_org_type = !empty($sent_box_model->source_org_type) ? $sent_box_model->source_org_type : 'UNION';
                $sent_box_model->uuid = Yii::$app->getDb()->createCommand('SELECT NEWID() as id')->queryScalar();
                $save_model[] = $sent_box_model;
            }
            $generalModel = new GeneralModel();
            $transaction = $generalModel->saveTransaction($save_model, ['Sent Box', 'create']);
            if ($transaction == 'customRedirect') {
                return true;
            }
        }
        return true;
    }

    private function parentEntry($model, $operation, $sentModel) {
        $this->entry($model, $operation, $sentModel);
        return $sentModel->save();
    }

    public function entry($model, $operation, $sentModel) {
        $microtime = date("Y-m-d H:i:s.") . gettimeofday()["usec"];
        if ((strpos($model->tableName(), 'local') || $model->tableName() == 'tbl_message_property') && $model->tableName() != 'tbl_local_milk_sale_rate') {
            $sentModel->language_code = $model->language_code;
        }
        //        $sentModel->uuid = $this->getUUID();
        $sentModel->table_name = !empty($sentModel->table_name) ? $sentModel->table_name : $model->tableName();
        $sentModel->operation = $operation;
        $sentModel->json_text = !empty($sentModel->json_text) ? $sentModel->json_text : Json::encode($this->jsonModel($model), JSON_UNESCAPED_UNICODE);
        $sentModel->message_type = 'RECORD';
        //        $sentModel->processed = 0;
        //   $this->column_sequence = implode(',', $model->getTableSchema()->getColumnNames());
        $sentModel->sync_status = 'U';
        $sentModel->sync_timestamp = $microtime;
        $sentModel->posting_timestamp = !empty($sentModel->posting_timestamp) ? $sentModel->posting_timestamp : $microtime;
        //        $sentModel->transmitted = 0;
        //        $sentModel->is_origin = 1;
        $sentModel->originating_org_id = Yii::$app->session->get('organizations_code');
        $sentModel->originating_org_type = Yii::$app->session->get('organizations_type');
        //        $sentModel->source_org_id = ''; //Yii::$app->session->get('organizations_code');
        $sentModel->source_org_type = Yii::$app->session->get('organizations_type');
        $sentModel->sequence_no = 5;
        $sentModel->source_device_mac = Yii::$app->session->get('MacAddress');
        //        $sentModel->operation_condition = (in_array($model->tableName(), array_keys($this->priority_array))) ? $this->priority_array[$model->tableName()] : '8';
    }

    public function getUUID() {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand('SELECT NEWID() as id')->queryOne();
        return $command['id'];
    }

    public function getData($notInTables = []) {
        $data = $this->find()
                ->where(['dest_org_id' => $this->dest_org_id, 'dest_org_type' => $this->dest_org_type, 'device_id' => $this->device_id])
                ->andWhere(['NOT IN', 'table_name', $notInTables])
                ->orderBy('posting_timestamp')
                ->limit(5)
                ->all();
        $response = [];
        foreach ($data as $key => $model) {
            $response[] = $this->jsonModel($model);
        }
        return $response;
    }

    public function getExportDataDcsNew($device_id, $dest_org_id, $dest_org_type) {
        $query = TblSentbox::find()->select(['dest_org_id', 'dest_org_type', 'error_log', 'error_timestamp', 'json_text', 'message_type', 'operation', 'originating_org_id', 'originating_org_type', 'posting_timestamp', 'sequence_no', 'source_device_mac', 'source_org_id', 'source_org_type', 'sync_status', 'sync_timestamp', 'table_name', 'uuid', 'version_no'])
                        ->where(['dest_org_id' => $dest_org_id, 'dest_org_type' => $dest_org_type, 'device_id' => $device_id])
                        ->andWhere(['sync_status' => 'U', 'message_type' => 'RECORD'])->orderBy('posting_timestamp');
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->asArray()->all(),
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    public function getDataCount($notInTables = []) {
        $data = $this->find()
                ->where(['dest_org_id' => $this->dest_org_id, 'dest_org_type' => $this->dest_org_type, 'device_id' => $this->device_id])
                ->andWhere(['NOT IN', 'table_name', $notInTables])
                ->count();
        return $data;
    }

    public function setSentboxDownload($model, $operation, $count = 1) {

        $addressBook = $this->isAddressBook(!empty($this->table_name) ? $this->table_name : $model->tableName());
        foreach ($addressBook as $d) {
            $sentModel = new TblSentbox();
            $attribute = $this->attributes;
            $sentModel->setAttributes($attribute);
            switch ($d->destinations) {
                case 0:
                    $this->childEntryDownload($model, $operation, $sentModel);
                    break;
                case 1:
                    if ($d->to_child == 1) {
                        $table = !empty($this->table_name) ? $this->table_name : $model->tableName();
                        if (in_array($table, ['tbl_user_organization_mapping', 'user'])) {
                            if ($model->entry_type == 1) {
                                $this->childEntryDownload($model, $operation, $sentModel);
                            }
                        } else {
                            $this->childEntryDownload($model, $operation, $sentModel);
                        }
                    }
                    if ($d->to_parent == 1 && $count == 1) {
                        $sentModel = new TblSentbox();
                        $attribute = $this->attributes;
                        $sentModel->setAttributes($attribute);
                        $this->parentEntryDownload($model, $operation, $sentModel);
                    }
                    break;
                case 2:
                    $this->childEntryDownload($model, $operation, $sentModel);
                    $sentModel = new TblSentbox();
                    $attribute = $this->attributes;
                    $sentModel->setAttributes($attribute);
                    if ($count == 1)
                        $this->parentEntryDownload($model, $operation, $sentModel);
                    break;
                default:
                    break;
            }
        }
        return $sentModel;
    }

    private function parentEntryDownload($model, $operation, $sentModel) {
        $this->entry($model, $operation, $sentModel);
        return $sentModel;
    }

    private function childEntryDownload($model, $operation, $sentModel) {

        $destination = '';
        switch (Yii::$app->session->get('organizations_type')) {
            case 'NATIONAL':
                $destination = 'FEDERATION';
                break;
            case 'FEDERATION':
                $destination = 'UNION';
                break;
            case 'UNION':
                $destination = 'DCS';
                break;
        }
        $this->entrydownload($model, $operation, $sentModel);
        $sentModel->dest_org_id = !empty($sentModel->dest_org_id) ? $sentModel->dest_org_id : '0';
        $sentModel->dest_org_type = !empty($sentModel->dest_org_type) ? $sentModel->dest_org_type : $destination;
        if ($sentModel->table_name == 'tbl_route_mapping') {
            $sentModel->table_name = 'tbl_route';
        } else if ($sentModel->table_name == 'tbl_route_mapping_sources') {
            $sentModel->table_name = 'tbl_route_mapping';
        }
        $data = $sentModel->attributes;
        $model = new TblAndroidInstallationDetails();
        $model_data = $model->getActiveDeviceData($sentModel->dest_org_id, $sentModel->dest_org_type, $sentModel->device_id);
        $save_model = [];
        $connection = Yii::$app->getDb();
        if (!empty($model_data)) {
            foreach ($model_data as $device) {
                $sent_box_model = new TblSentbox();
                $sent_box_model->setAttributes($data);
                $sent_box_model->device_id = !empty($device->device_id) ? $device->device_id : '';
                $command = $connection->createCommand('SELECT NEWID() as id')->queryOne();
                $sent_box_model->uuid = $command['id'];
                $save_model[] = $sent_box_model;
            }
            return $save_model;
        }
        return $save_model;
    }

    public function entrydownload($model, $operation, $sentModel) {
        $microtime = date("Y-m-d H:i:s.") . gettimeofday()["usec"];
        if (strpos($model->tableName(), 'local') || $model->tableName() == 'tbl_message_property') {
            $sentModel->language_code = $model->language_code;
        }
        //        $sentModel->uuid = $this->getUUID();
        $sentModel->table_name = !empty($sentModel->table_name) ? $sentModel->table_name : $model->tableName();
        $sentModel->operation = $operation;
        $sentModel->json_text = !empty($sentModel->json_text) ? $sentModel->json_text : Json::encode($this->jsonModel($model), JSON_UNESCAPED_UNICODE);
        $sentModel->message_type = 'RECORD';
        //        $sentModel->processed = 0;
        //   $this->column_sequence = implode(',', $model->getTableSchema()->getColumnNames());
        $sentModel->sync_status = 'U';
        $sentModel->sync_timestamp = $microtime;
        $sentModel->posting_timestamp = $microtime;
        //        $sentModel->transmitted = 0;
        //        $sentModel->is_origin = 1;
        $sentModel->originating_org_id = Yii::$app->session->get('organizations_code');
        $sentModel->originating_org_type = Yii::$app->session->get('organizations_type');
        //        $sentModel->source_org_id = ''; //Yii::$app->session->get('organizations_code');
        $sentModel->source_org_type = Yii::$app->session->get('organizations_type');
        $sentModel->sequence_no = 5;
        $sentModel->source_device_mac = Yii::$app->session->get('MacAddress');
        //        $sentModel->operation_condition = (in_array($model->tableName(), array_keys($this->priority_array))) ? $this->priority_array[$model->tableName()] : '8';
    }

    public function jsonModel($model) {
        $newModel = null;
        $scema = $model->getTableSchema();
        foreach ($model->attributes as $key => $a) {
            if ($scema->name == 'tbl_ledger' && $key == 'has_sub_ledger') {
                
            } else {
                //            if ($key == 'is_active' || $key == 'is_delete' || $key=='is_milch' || $key=='is_default' || $key=='is_balance_sheet' || $key=='is_profit_loss') {
                //                $new_key = str_replace('is_', '', $key);
                //                $new_key = str_replace('_', ' ', $new_key);
                //                $new_key = ucwords($new_key);
                //                $new_key = str_replace(' ', '', $new_key);
                //                $new_key = lcfirst($new_key);
                //                $newModel[$new_key] = ($a==1)?true:false;
                //            } else {

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
                $new_key = (in_array($new_key, array('NATIONAL', 'UNION', 'FEDERATION'))) ? $new_key : lcfirst($new_key);

                $newModel[$new_key] = $a;
                // }
            }
        }
        return (object) $newModel;
    }

    public function setSentboxBatch($model, $operation, $sentboxArray) {
        $addressBook = $this->isAddressBook(!empty($this->table_name) ? $this->table_name : $model->tableName());
        if (!empty($addressBook) && $addressBook[0]->destinations == 1) {
            $sentModel = new TblSentbox();
            $sentModel->setAttributes($this->attributes);
            $this->childEntryBatch($model, $operation, $sentModel, $sentboxArray);
        }
        return TRUE;
    }

    private function childEntryBatch($model, $operation, $sentModel, $sentboxArray) {
        $save_model = [];
        $this->entry($model, $operation, $sentModel);
        $destOrgId = array_map(function($sent) { 
            return $sent['code'] ?: '0'; 
        }, $sentboxArray);
        $destOrgType = array_unique(array_column($sentboxArray, 'type'));
        $androidInstallationDetailsModel = new TblAndroidInstallationDetails();
        $deviceData = $androidInstallationDetailsModel->getActiveDeviceDataForOrganizations($destOrgId, $destOrgType, $sentModel->device_id);
        $data = $sentModel->attributes;
        foreach ($deviceData as $device) {
            $sent_box_model = new TblSentbox();
            $sent_box_model->setAttributes($data);
            $sent_box_model->device_id = $device['device_id'];
            $sent_box_model->dest_org_type = $device['organization_type'];
            $sent_box_model->dest_org_id = $device['organization_code'];
            $sent_box_model->originating_org_type = $sent_box_model->source_org_type =  'UNION';
            $sent_box_model->originating_org_id  = $sent_box_model->source_org_id = !empty($sent_box_model->source_org_id) ? $sent_box_model->source_org_id : '001';
            $sent_box_model->data_post_status = 0;
            $save_model[] = $sent_box_model;
        }
        if (!empty($save_model)) {
            $attributes = $save_model[0]->attributes();
            $chunks = array_chunk($save_model, 1000);
            foreach ($chunks as $chunk) {
                $rows = array_map(function($model) {
                    $model->uuid = new Expression('NEWID()');
                    return $model->attributes;
                }, $chunk);
                Yii::$app->getDb()->createCommand()->batchInsert('tbl_sentbox', $attributes, $rows)->execute();
            }
        }
        return true;
    }

}
