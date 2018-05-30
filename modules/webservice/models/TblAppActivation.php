<?php

namespace app\modules\webservice\models;

use Yii;

/**
 * This is the model class for table "tbl_app_activation".
 *
 * @property integer $activation_id
 * @property integer $type
 * @property string $code
 * @property string $mobile_no
 * @property string $imei_no
 * @property string $orignating_timestamp
 * @property string $posting_timestamp
 * @property integer $sms_sent
 * @property string $sms_log
 * @property integer $otp_code
 * @property string $hash_key
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property integer $is_expired
 * @property string $expired_datetime
 * @property string $device_id
 */
class TblAppActivation extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_app_activation';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type', 'sms_sent', 'otp_code', 'is_active', 'is_delete'], 'required'],
            [['type', 'sms_sent', 'otp_code', 'is_active', 'is_delete', 'is_expired'], 'integer'],
            [['code', 'mobile_no', 'imei_no', 'sms_log', 'hash_key', 'flg_sentbox_entry', 'sync_status', 'device_id'], 'string'],
            [['orignating_timestamp', 'posting_timestamp', 'sync_timestamp', 'updated_at', 'expired_datetime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'activation_id' => Yii::t('app', 'Activation ID'),
            'type' => Yii::t('app', 'Type'),
            'code' => Yii::t('app', 'Code'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'imei_no' => Yii::t('app', 'Imei No'),
            'orignating_timestamp' => Yii::t('app', 'Orignating Timestamp'),
            'posting_timestamp' => Yii::t('app', 'Posting Timestamp'),
            'sms_sent' => Yii::t('app', 'Sms Sent'),
            'sms_log' => Yii::t('app', 'Sms Log'),
            'otp_code' => Yii::t('app', 'Otp Code'),
            'hash_key' => Yii::t('app', 'Hash Key'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'is_expired' => Yii::t('app', 'Is Expired'),
            'expired_datetime' => Yii::t('app', 'Expired Datetime'),
            'device_id' => Yii::t('app', 'Device ID'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblAppActivationQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblAppActivationQuery(get_called_class());
    }

    public function activationInfo($data) {
        return $this->find()->where(['imei_no' => $this->imei_no, 'type' => $this->type, 'hash_key' => $data['token'], 'otp_code' => $this->otp_code, 'is_expired' => 0])->one();
    }

    public function getActiveRecord() {
        return $this->find()->where(['type' => $this->type, 'code' => $this->code, 'imei_no' => $this->imei_no, 'hash_key' => $this->hash_key, 'is_active' => 1, 'is_expired' => 0])->count();
    }

    public function getOldRecord($encryptedmobile) {
        return $this->find()->where(['mobile_no' => $encryptedmobile, 'type' => $this->type, 'imei_no' => $this->imei_no, 'is_expired' => 0])->all();
    }

    public function getCount($field_name, $value) {
        return $this->find()->where(['type' => $this->type, 'hash_key' => $this->hash_key, 'imei_no' => $this->imei_no, $field_name => $value])->count();
    }

    public function activationDetail() {
        return $this->find()->where(['type' => $this->type, 'code' => $this->code, 'imei_no' => $this->imei_no, 'hash_key' => $this->hash_key, 'is_active' => 1, 'is_expired' => 0])->one();
    }

    public function rateChartDetail($field, $purchase_rate_code) {
        return $this->find()->where([$field => $purchase_rate_code])->one();
    }

    public function activationData($encryptedmobile) {
        return $this->find()
                        ->where(['code' => $this->code])
                        ->andWhere(['or', ['mobile_no' => $this->mobile_no], ['mobile_no' => $encryptedmobile]])
                        ->one();
    }

    public function userActivationInfo($data) {
        return $this->find()->where(['imei_no' => $data['imei'], 'type' => $data['type'], 'hash_key' => $data['token'], 'is_active' => 1, 'is_expired' => 0])->one();
    }

}
