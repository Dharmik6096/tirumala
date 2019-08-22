<?php

namespace app\modules\webservice\eipl\models;

use Yii;
use app\modules\webservice\eipl\models\TblEiplAppLogin;

/**
 * This is the model class for table "tbl_dpu_collection_ho_data".
 *
 * @property integer $dpu_collection_ho_data_id
 * @property string $uuid
 * @property string $access_token
 * @property string $device_id
 * @property string $encrypted_string
 * @property string $identity_type
 * @property string $mobile_no
 * @property string $entry_type
 * @property string $entry_datetime
 * @property string $status
 * @property string $pick_datetime
 * @property string $response_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblDpuCollectionHoData extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dpu_collection_ho_data';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid', 'access_token', 'device_id', 'encrypted_string', 'identity_type', 'mobile_no', 'entry_type', 'status', 'created_by', 'updated_by'], 'safe'],
            [['entry_datetime', 'pick_datetime', 'response_datetime', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dpu_collection_ho_data_id' => Yii::t('app', 'Dpu Collection Ho Data ID'),
            'uuid' => Yii::t('app', 'Uuid'),
            'access_token' => Yii::t('app', 'Access Token'),
            'device_id' => Yii::t('app', 'Device ID'),
            'encrypted_string' => Yii::t('app', 'Encrypted String'),
            'identity_type' => Yii::t('app', 'Identity Type'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'entry_datetime' => Yii::t('app', 'Entry Datetime'),
            'status' => Yii::t('app', 'Status'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getData() {
        $newRecords = $this->find()
                        ->where(['or', ['status' => 0], ['status' => NULL]])
                        ->limit(50)
                        ->all();
        
        $unprocessedRecords = $this->find()
                        ->where(['status' => 3])
                        ->limit(10)
                        ->all();
        return array_merge($newRecords, $unprocessedRecords);
    }

}
