<?php

namespace app\modules\webservice\eipl\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_app_login_history".
 *
 * @property integer $id
 * @property integer $app_login_id
 * @property integer $app_type
 * @property string $eipl_code
 * @property string $mobile_no
 * @property string $master_type
 * @property string $master_code
 * @property string $login_type
 * @property string $module_type
 * @property string $module_code
 * @property integer $otp_code
 * @property string $imei_no
 * @property string $device_id
 * @property string $lat_long
 * @property string $access_token
 * @property string $auth_key
 * @property string $version_no
 * @property string $orignating_timestamp
 * @property string $posting_timestamp
 * @property integer $sms_sent
 * @property string $sms_log
 * @property integer $is_active
 * @property integer $is_expired
 * @property string $expired_datetime
 * @property string $updated_at
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblEiplAppLoginHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_login_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['app_login_id', 'app_type', 'otp_code', 'sms_sent', 'is_active', 'is_expired'], 'integer'],
            [['eipl_code', 'mobile_no', 'master_type', 'master_code', 'login_type', 'module_type', 'module_code', 'imei_no', 'device_id', 'lat_long', 'access_token', 'auth_key', 'version_no', 'sms_log', 'operation_type', 'history_created_by'], 'string'],
            [['orignating_timestamp', 'posting_timestamp', 'expired_datetime', 'updated_at', 'history_created_at', 'department'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'app_login_id' => Yii::t('app', 'App Login ID'),
            'app_type' => Yii::t('app', 'App Type'),
            'eipl_code' => Yii::t('app', 'Eipl Code'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'master_type' => Yii::t('app', 'Master Type'),
            'master_code' => Yii::t('app', 'Master Code'),
            'login_type' => Yii::t('app', 'Login Type'),
            'module_type' => Yii::t('app', 'Module Type'),
            'module_code' => Yii::t('app', 'Module Code'),
            'otp_code' => Yii::t('app', 'Otp Code'),
            'imei_no' => Yii::t('app', 'Imei No'),
            'device_id' => Yii::t('app', 'Device ID'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'access_token' => Yii::t('app', 'Access Token'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'version_no' => Yii::t('app', 'Version No'),
            'orignating_timestamp' => Yii::t('app', 'Orignating Timestamp'),
            'posting_timestamp' => Yii::t('app', 'Posting Timestamp'),
            'sms_sent' => Yii::t('app', 'Sms Sent'),
            'sms_log' => Yii::t('app', 'Sms Log'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_expired' => Yii::t('app', 'Is Expired'),
            'expired_datetime' => Yii::t('app', 'Expired Datetime'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
