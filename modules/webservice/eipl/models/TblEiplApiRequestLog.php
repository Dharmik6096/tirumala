<?php

namespace app\modules\webservice\eipl\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_api_request_log".
 *
 * @property integer $log_id
 * @property string $access_token
 * @property string $request_url
 * @property string $request_time
 * @property string $request_body
 * @property string $lat_long
 * @property string $device_id
 * @property string $version_no
 */
class TblEiplApiRequestLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_eipl_api_request_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['access_token', 'request_url', 'request_body', 'lat_long', 'device_id', 'version_no'], 'string'],
            [['request_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'access_token' => Yii::t('app', 'Access Token'),
            'request_url' => Yii::t('app', 'Request Url'),
            'request_time' => Yii::t('app', 'Request Time'),
            'request_body' => Yii::t('app', 'Request Body'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'device_id' => Yii::t('app', 'Device ID'),
            'version_no' => Yii::t('app', 'Version No'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblEiplApiRequestLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblEiplApiRequestLogQuery(get_called_class());
    }
}
