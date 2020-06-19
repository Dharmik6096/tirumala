<?php

namespace app\modules\webservice\eipl\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_app_login_temp".
 *
 * @property integer $app_login_id
 * @property integer $app_type
 * @property string $eipl_code
 * @property string $mobile_no
 * @property integer $otp_code
 * @property string $imei_no
 * @property string $device_id
 * @property string $lat_long
 * @property string $version_no
 * @property string $orignating_timestamp
 * @property string $posting_timestamp
 */
class TblEiplAppLoginTemp extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_login_temp';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['orignating_timestamp', 'posting_timestamp'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['app_type', 'otp_code'], 'safe'],
            [['eipl_code', 'mobile_no', 'imei_no', 'device_id', 'lat_long', 'version_no'], 'safe'],
            [['orignating_timestamp', 'posting_timestamp'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'app_login_id' => Yii::t('app', 'App Login ID'),
            'app_type' => Yii::t('app', 'App Type'),
            'eipl_code' => Yii::t('app', 'Eipl Code'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'otp_code' => Yii::t('app', 'Otp Code'),
            'imei_no' => Yii::t('app', 'Imei No'),
            'device_id' => Yii::t('app', 'Device ID'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'version_no' => Yii::t('app', 'Version No'),
            'orignating_timestamp' => Yii::t('app', 'Orignating Timestamp'),
            'posting_timestamp' => Yii::t('app', 'Posting Timestamp'),
        ];
    }

    public function activationInfo() {
        $datetime = date('Y-m-d H:i:s', strtotime("-5 minutes", strtotime(date('Y-m-d H:i:s'))));
        $encryptedmobile = Yii::$app->general->encryptData($this->mobile_no);
        return $this->find()->where(['app_type' => $this->app_type, 'otp_code' => $this->otp_code])
                        ->andWhere(['or',
                            ['mobile_no' => $encryptedmobile],
                            ['mobile_no' => $this->mobile_no]
                        ])
                        ->andWhere(['>=', 'orignating_timestamp', $datetime])
                        ->one();
    }

    public function getAppTempLogin() {
        $encryptedmobile = Yii::$app->general->encryptData($this->mobile_no);
        return $query = $this->find()
                        ->where(['or',
                            ['mobile_no' => $encryptedmobile],
                            ['mobile_no' => $this->mobile_no]
                        ])->all();
    }

}
