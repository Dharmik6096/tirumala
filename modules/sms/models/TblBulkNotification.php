<?php

namespace app\modules\sms\models;

use Yii;

/**
 * This is the model class for table "tbl_bulk_notification".
 *
 * @property integer $bulk_notification_id
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $member_code
 * @property string $app_type
 * @property string $login_type
 * @property string $wef_date
 * @property string $title
 * @property string $message
 * @property string $campaign_name
 * @property string $created_at
 * @property string $created_by
 * @property integer $receiver_type
 * @property integer $content_id
 * @property integer $status
 * @property string $entry_datetime
 * @property string $pickup_datetime
 * @property string $response_datetime
 */
class TblBulkNotification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bulk_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'app_type', 'login_type', 'title', 'message', 'campaign_name', 'created_by'], 'string'],
            [['wef_date', 'created_at', 'entry_datetime', 'pickup_datetime', 'response_datetime'], 'safe'],
            [['receiver_type', 'content_id', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bulk_notification_id' => Yii::t('app', 'Bulk Notification ID'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'app_type' => Yii::t('app', 'App Type'),
            'login_type' => Yii::t('app', 'Login Type'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'title' => Yii::t('app', 'Title'),
            'message' => Yii::t('app', 'Message'),
            'campaign_name' => Yii::t('app', 'Campaign Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'receiver_type' => Yii::t('app', 'Receiver Type'),
            'content_id' => Yii::t('app', 'Content ID'),
            'status' => Yii::t('app', 'Status'),
            'entry_datetime' => Yii::t('app', 'Entry Datetime'),
            'pickup_datetime' => Yii::t('app', 'Pickup Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
        ];
    }
}
