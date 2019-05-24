<?php

namespace app\modules\installation\models;

use Yii;
use yii\helpers\ArrayHelper;

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
 * @property string $db_path
 * @property string $use_for
 * @property string $lat
 * @property string $long
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $sync_key
 * @property integer $sync_active
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
            [['created_at', 'updated_at', 'db_path', 'imei_no', 'sync_key', 'sync_active'], 'safe'],
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
            'db_path' => Yii::t('app', 'Db Path'),
        ];
    }

    public function getData() {
        return $this->find()
                        ->where(['imei_no' => $this->imei_no, 'hash_key' => $this->hash_key, 'otp_code' => $this->otp_code, 'is_expired' => 0])
                        ->one();
    }

    public function getAndroidInstallationCode() {
        return $this->hasOne(TblAndroidInstallation::className(), ['android_installation_id' => 'android_installation_id']);
    }

    public function getActiveData($data) {
        return $this->find()
                        ->select('tbl_android_installation_details.*')
                        ->joinWith(['androidInstallationCode'])
                        ->where(['tbl_android_installation_details.hash_key' => $data['token'], 'tbl_android_installation_details.imei_no' => $data['imei'], 'tbl_android_installation_details.is_active' => 1, 'tbl_android_installation_details.is_expired' => 0, 'tbl_android_installation_details.device_id' => $data['device_id']])
                        ->andWhere(['tbl_android_installation.organization_code' => $data['organization_code'], 'tbl_android_installation.organization_type' => $data['organization_type']])
                        ->one();
    }

    public function getActiveRecordCount($data) {
        return $this->find()
                        ->select('tbl_android_installation_details.*')
                        ->joinWith(['androidInstallationCode'])
                        ->where(['tbl_android_installation_details.hash_key' => $data['token'], 'tbl_android_installation_details.imei_no' => $data['imei'], 'tbl_android_installation_details.is_active' => 1, 'tbl_android_installation_details.is_expired' => 0, 'tbl_android_installation_details.device_id' => $data['device_id']])
                        ->andWhere(['tbl_android_installation.organization_code' => $data['organization_code'], 'tbl_android_installation.organization_type' => $data['organization_type']])
                        ->count();
    }

    public function getActiveCount() {
        return $this->find()
                        ->where(['android_installation_id' => $this->android_installation_id, 'device_id' => $this->device_id, 'mobile_no' => $this->mobile_no, 'is_active' => 1])
                        ->one();
    }

    public function getActiveDeviceData($dest_org_id, $dest_org_type) {
        return $this->find()
                        ->select('tbl_android_installation_details.device_id')
                        ->distinct()
                        ->joinWith(['androidInstallationCode'])
                        ->where(['tbl_android_installation_details.is_active' => 1, 'tbl_android_installation_details.is_expired' => 0])
                        ->andWhere(['tbl_android_installation.organization_code' => $dest_org_id, 'tbl_android_installation.organization_type' => $dest_org_type])
                        ->all();
    }

    public function getRecords() {
        $data = $this->find()
                ->where(['android_installation_id' => $this->android_installation_id])
                ->andWhere(['not in', 'android_installation_details_id', $this->android_installation_details_id])
                ->all();
        return ArrayHelper::map($data, 'android_installation_details_id', 'android_installation_details_id');
    }

    public function getSyncActiveData($data) {
        return $this->find()
                        ->select('tbl_android_installation_details.*')
                        ->joinWith(['androidInstallationCode'])
                        ->where(['tbl_android_installation_details.hash_key' => $data['token'], 'tbl_android_installation_details.imei_no' => $data['imei'], 'tbl_android_installation_details.is_active' => 1, 'tbl_android_installation_details.is_expired' => 0, 'tbl_android_installation_details.device_id' => $data['device_id'], 'tbl_android_installation_details.sync_key' => $data['sync_key'], 'tbl_android_installation_details.sync_active' => 1])
                        ->andWhere(['tbl_android_installation.organization_code' => $data['organization_code'], 'tbl_android_installation.organization_type' => $data['organization_type']])
                        ->one();
    }

}
