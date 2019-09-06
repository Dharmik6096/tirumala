<?php

namespace app\modules\syncutility\models;

use Yii;

/**
 * This is the model class for table "tbl_realtime_sync_error".
 *
 * @property integer $sync_error_code
 * @property string $table_name
 * @property string $url
 * @property string $operation_type
 * @property string $organization_code
 * @property string $organization_type
 * @property string $json_text
 * @property string $token
 * @property integer $send_status
 * @property string $validation_error
 * @property string $transaction_error
 * @property string $svc
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $response_status
 */
class TblRealtimeSyncError extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_realtime_sync_error';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_name', 'url', 'operation_type', 'organization_code', 'organization_type', 'json_text', 'token', 'validation_error', 'transaction_error', 'svc', 'created_by', 'updated_by'], 'safe'],
            [['send_status', 'response_status'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sync_error_code' => Yii::t('app', 'Sync Error Code'),
            'table_name' => Yii::t('app', 'Table Name'),
            'url' => Yii::t('app', 'Url'),
            'operation_type' => Yii::t('app', 'Opertaion Type'),
            'organization_code' => Yii::t('app', 'Organization Code'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'json_text' => Yii::t('app', 'Json Text'),
            'token' => Yii::t('app', 'Token'),
            'send_status' => Yii::t('app', 'Send Status'),
            'validation_error' => Yii::t('app', 'Validation Error'),
            'transaction_error' => Yii::t('app', 'Transaction Error'),
            'svc' => Yii::t('app', 'Svc'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'response_status' => Yii::t('app', 'Response Status'),
        ];
    }
}
