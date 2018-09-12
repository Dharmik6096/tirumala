<?php

namespace app\modules\vendorapi\models;

use Yii;

/**
 * This is the model class for table "tbl_vendor_api_request_log".
 *
 * @property integer $log_id
 * @property string $union_code
 * @property string $url
 * @property string $request
 * @property string $response
 * @property integer $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $request_original
 * @property string $request_ip
 * @property string $error_log
 */
class TblVendorApiRequestLog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_vendor_api_request_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['union_code', 'url', 'request', 'response', 'created_by', 'updated_by', 'request_original', 'request_ip', 'error_log'], 'safe'],
            [['status'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'union_code' => Yii::t('app', 'Union Code'),
            'url' => Yii::t('app', 'Url'),
            'request' => Yii::t('app', 'Request'),
            'response' => Yii::t('app', 'Response'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'request_original' => Yii::t('app', 'Request Original'),
            'request_ip' => Yii::t('app', 'Request Ip'),
            'error_log' => Yii::t('app', 'Error Log'),
        ];
    }

}
