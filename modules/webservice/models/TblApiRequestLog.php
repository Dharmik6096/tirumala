<?php

namespace app\modules\webservice\models;

use Yii;

/**
 * This is the model class for table "tbl_api_request_log".
 *
 * @property integer $log_id
 * @property string $hash_key
 * @property string $request_url
 * @property string $request_time
 * @property string $imei
 * @property string $identity_code
 * @property string $member_code
 * @property string $type
 * @property integer $is_called
 * @property string $content
 */
class TblApiRequestLog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_api_request_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['hash_key', 'request_url', 'imei', 'identity_code', 'member_code', 'type', 'content'], 'safe'],
            [['request_time'], 'safe'],
            [['is_called'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'hash_key' => Yii::t('app', 'Hash Key'),
            'request_url' => Yii::t('app', 'Request Url'),
            'request_time' => Yii::t('app', 'Request Time'),
            'imei' => Yii::t('app', 'Imei'),
            'identity_code' => Yii::t('app', 'Identity Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'type' => Yii::t('app', 'Type'),
            'is_called' => Yii::t('app', 'Is Called'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

}
