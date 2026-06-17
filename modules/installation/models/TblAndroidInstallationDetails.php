<?php

namespace app\modules\installation\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\dcsoperation\models\TblRateDownloadAck;
use app\models\GeneralModel;

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
                [['android_installation_id', 'mobile_no', 'hash_key', 'device_id', 'device_type', 'use_for', 'lat', 'long', 'created_by', 'updated_by'], 'safe'],
                [['otp_code', 'is_active', 'is_expired', 'd2d_request'], 'safe'],
                [['created_at', 'updated_at', 'db_path', 'imei_no', 'sync_key', 'sync_active', 'db_version', 'installation_type', 'version_no', 'password', 'password_date'], 'safe'],
                [['installation_type'], 'default', 'value' => 0],
                [['d2d_request'], 'default', 'value' => 0],
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
            'created_at' => Yii::t('app', 'Created Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'db_path' => Yii::t('app', 'Db Path'),
            'db_version' => Yii::t('app', 'Version'),
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
                        ->where(['tbl_android_installation_details.hash_key' => $data['token'], 'tbl_android_installation_details.is_active' => 1, 'tbl_android_installation_details.is_expired' => 0, 'tbl_android_installation_details.device_id' => $data['device_id']])
                        ->andWhere(['tbl_android_installation.organization_code' => $data['organization_code'], 'tbl_android_installation.organization_type' => $data['organization_type']])
                        ->one();
    }

    public function getActiveRecordCount($data) {
        if ($data['organization_type'] == 'VLC' && $data['device_id'] != '' && $data['device_id'] != NULL) {
            $record = $this->find()
                    // ->where(['hask_key' => $data['token'], 'organization_type' => 'VLC', 'is_active' => 1, 'is_expired' => 0, 'organization_code' => $data['organization_code']])
                    ->where(['hash_key' => $data['token']])
                    ->one();
            if (!empty($record) && (empty($record->device_id) || $record->is_active == 0)) {
                $record->device_id = $data['device_id'];
                $record->is_active = 1;
                $childModel = [];
                $delete = [];
                $existDetailData = $this->find()->where(['android_installation_id' => $record->android_installation_id])
                        ->andWhere(['!=', 'android_installation_details_id', $record->android_installation_details_id])
                        ->all();
                foreach ($existDetailData as $detail) {
                    $historyModel = new TblAndroidInstallationDetailsHistory();
                    Yii::$app->operation->history($detail, $historyModel, 'DELETE');
                    $childModel[] = $historyModel;
                    $delete[] = $detail;
                }
                $generalModel = new GeneralModel();
                $transaction = $generalModel->saveDeleteTransaction([$record], $childModel, $delete, ['identity', 'edit']);
                $rate_app_data = new TblRateDownloadAck();
                $rate_app_data->updateAll(['device_id' => $record->device_id, 'download_date_time' => date('Y-m-d H:i:s')], ['applicable_code' => $data['organization_code'], 'applicable_for' => 'MEMBER', 'device_id' => NULL]);
            }
        }
        return $this->find()
                        ->select('tbl_android_installation_details.*')
                        ->joinWith(['androidInstallationCode'])
                        ->where(['tbl_android_installation_details.hash_key' => $data['token'], 'tbl_android_installation_details.is_active' => 1, 'tbl_android_installation_details.is_expired' => 0, 'tbl_android_installation_details.device_id' => $data['device_id']])
                        ->andWhere(['tbl_android_installation.organization_code' => $data['organization_code'], 'tbl_android_installation.organization_type' => $data['organization_type']])
                        ->count();
    }

    public function getActiveCount() {
        return $this->find()
                        ->where(['android_installation_id' => $this->android_installation_id, 'device_id' => $this->device_id, 'mobile_no' => $this->mobile_no, 'is_active' => 1])
                        ->one();
    }

    public function getActiveDeviceData($dest_org_id, $dest_org_type, $device = '') {
        $isArray = is_array($dest_org_id);
        $query = $this->find()
                ->select($isArray ? ['tbl_android_installation_details.device_id', 'tbl_android_installation.organization_code'] : 'tbl_android_installation_details.device_id')
                ->distinct()
                ->where([
            'tbl_android_installation_details.is_active' => 1,
            'tbl_android_installation_details.is_expired' => 0,
            'tbl_android_installation.organization_type' => (string) $dest_org_type
        ]);

        if ($isArray) {
            $query->innerJoin('tbl_android_installation', 'tbl_android_installation.android_installation_id = tbl_android_installation_details.android_installation_id');
            $org_string = "'" . implode(',', $dest_org_id) . "'";
            $command_dest_org_id = Yii::$app->db->createCommand("SELECT distinct code from [SplitToTable](" . $org_string . ",',')");
            $organization_code = $command_dest_org_id->sql;
            $query->andWhere('tbl_android_installation.organization_code in (' . $organization_code . ')');
        } else {
            $query->joinWith(['androidInstallationCode']);
            $query->andWhere(['tbl_android_installation.organization_code' => (string) $dest_org_id]);
        }

        if (!empty($device)) {
            $query->andWhere(['tbl_android_installation_details.device_id' => $device]);
        }

        if ($isArray) {
            return $query->asArray()->all();
        }
        return $query->all();
    }

    public function getActiveDeviceDataForOrganizations($dest_org_id, $dest_org_type, $device = '') {
        $org_string = "'" . implode(',', $dest_org_id) . "'";
        $organization_type = "'" . implode("','", $dest_org_type) . "'";
        $command_dest_org_id = Yii::$app->db->createCommand("SELECT distinct code from [SplitToTable](" . $org_string . ",',')");
        $organization_code = $command_dest_org_id->sql;

        $query = $this->find()
                ->select('tbl_android_installation_details.device_id, tbl_android_installation.organization_type, tbl_android_installation.organization_code')
                ->distinct()
                ->leftJoin('tbl_android_installation', "tbl_android_installation.android_installation_id = tbl_android_installation_details.android_installation_id")
                ->where(['tbl_android_installation_details.is_active' => 1, 'tbl_android_installation_details.is_expired' => 0])
                ->andWhere('tbl_android_installation.organization_code in (' . $organization_code . ')')
                ->andWhere('tbl_android_installation.organization_type in (' . $organization_type . ')');

        if (!empty($device)) {
            $query = $query->andWhere(['tbl_android_installation_details.device_id' => $device]);
        }
        return $query->asArray()->all();
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
                        ->where(['tbl_android_installation_details.hash_key' => $data['token'], 'tbl_android_installation_details.is_active' => 1, 'tbl_android_installation_details.is_expired' => 0, 'tbl_android_installation_details.device_id' => $data['device_id']])
                        ->andWhere(['tbl_android_installation.organization_code' => $data['organization_code'], 'tbl_android_installation.organization_type' => $data['organization_type']])
                        ->one();
    }

    public function getExistData($code) {
        return $this->find()->where(['android_installation_id' => $code, 'is_active' => 1])->all();
    }

}
