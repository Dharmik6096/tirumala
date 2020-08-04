<?php

namespace app\modules\installation\models;

use Yii;

/**
 * This is the model class for table "tbl_android_installation_details_history".
 *
 * @property integer $id
 * @property integer $android_installation_details_id
 * @property string $android_installation_id
 * @property string $mobile_no
 * @property integer $otp_code
 * @property string $hash_key
 * @property integer $is_active
 * @property integer $is_expired
 * @property string $device_id
 * @property string $device_type
 * @property string $db_path
 * @property string $use_for
 * @property string $lat
 * @property string $long
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $imei_no
 * @property string $sync_key
 * @property integer $sync_active
 * @property string $db_version
 * @property integer $installation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblAndroidInstallationDetailsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_android_installation_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['android_installation_details_id', 'otp_code', 'is_active', 'is_expired', 'sync_active', 'installation_type'], 'safe'],
            [['android_installation_id', 'mobile_no', 'hash_key', 'device_id', 'device_type', 'db_path', 'use_for', 'lat', 'long', 'created_by', 'updated_by', 'imei_no', 'sync_key', 'db_version', 'history_created_by', 'operation_type'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'password', 'password_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'android_installation_details_id' => Yii::t('app', 'Android Installation Details ID'),
            'android_installation_id' => Yii::t('app', 'Android Installation ID'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'otp_code' => Yii::t('app', 'Otp Code'),
            'hash_key' => Yii::t('app', 'Hash Key'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_expired' => Yii::t('app', 'Is Expired'),
            'device_id' => Yii::t('app', 'Device ID'),
            'device_type' => Yii::t('app', 'Device Type'),
            'db_path' => Yii::t('app', 'Db Path'),
            'use_for' => Yii::t('app', 'Use For'),
            'lat' => Yii::t('app', 'Lat'),
            'long' => Yii::t('app', 'Long'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'imei_no' => Yii::t('app', 'Imei No'),
            'sync_key' => Yii::t('app', 'Sync Key'),
            'sync_active' => Yii::t('app', 'Sync Active'),
            'db_version' => Yii::t('app', 'Db Version'),
            'installation_type' => Yii::t('app', 'Installation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
