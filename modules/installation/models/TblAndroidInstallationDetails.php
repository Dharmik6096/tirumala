<?php

namespace app\modules\installation\models;

use Yii;

/**
 * This is the model class for table "tbl_android_installation_details".
 *
 * @property integer $android_installation_details_id
 * @property string $android_installation_id
 * @property string $mobile_no
 * @property integer $otp_code
 * @property string $hash_key
 * @property integer $is_active
 * @property integer $is_expired
 * @property string $device_id
 * @property string $device_type
 * @property string $use_for
 * @property string $lat
 * @property string $long
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblAndroidInstallationDetails extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_android_installation_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['android_installation_id', 'mobile_no', 'hash_key', 'device_id', 'device_type', 'use_for', 'lat', 'long', 'created_by', 'updated_by'], 'string'],
            [['otp_code', 'is_active', 'is_expired'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'android_installation_details_id' => Yii::t('app', 'Android Installation Details ID'),
            'android_installation_id' => Yii::t('app', 'Android Installation ID'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'otp_code' => Yii::t('app', 'Otp Code'),
            'hash_key' => Yii::t('app', 'Hash Key'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_expired' => Yii::t('app', 'Is Expired'),
            'device_id' => Yii::t('app', 'Device ID'),
            'device_type' => Yii::t('app', 'Device Type'),
            'use_for' => Yii::t('app', 'Use For'),
            'lat' => Yii::t('app', 'Lat'),
            'long' => Yii::t('app', 'Long'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getData() {
        return $this->find()
                        ->where(['hash_key' => $this->hash_key, 'otp_code' => $this->otp_code])
                        ->one();
    }

    public function getAndroidInstallationCode() {
        return $this->hasOne(TblAndroidInstallation::className(), ['android_installation_id' => 'android_installation_id']);
    }

}
