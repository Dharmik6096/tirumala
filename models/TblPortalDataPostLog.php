<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_portal_data_post_log".
 *
 * @property integer $log_id
 * @property string $vendor_code
 * @property string $url
 * @property string $request
 * @property string $response
 * @property integer $status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblPortalDataPostLog extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_portal_data_post_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vendor_code', 'url', 'request', 'response', 'created_by', 'updated_by'], 'safe'],
            [['status'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'url' => Yii::t('app', 'Url'),
            'request' => Yii::t('app', 'Request'),
            'response' => Yii::t('app', 'Response'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
