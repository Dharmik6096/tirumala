<?php

namespace app\modules\sms\models;

use Yii;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_alert_notification".
 *
 * @property int $alert_notification_id
 * @property string $receiver_detail
 * @property string $receiver_type
 * @property string $message
 * @property string $header_info
 * @property string $status
 * @property string $send_status
 * @property string $response_status
 * @property string $refecence_code
 * @property string $module_type
 * @property string $entry_datetime
 * @property string $response_datetime
 */
class TblAlertNotificationPortal extends \app\models\ChildModel {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tbl_alert_notification_portal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['receiver_detail', 'receiver_type', 'message', 'header_info', 'status', 'send_status', 'refecence_code', 'module_type'], 'safe'],
            [['entry_datetime', 'response_datetime', 'created_by', 'send_mail', 'mail_receiver_detail', 'refecence_code', 'module_type', 'report_param', 'report_path'], 'safe'],
            [['mail_receiver_detail'], 'email', 'on' => ['sendMail']],
            [['mail_receiver_detail'], 'required', 'on' => ['sendMail']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'alert_notification_id' => Yii::t('app', 'Alert Notification ID'),
            'receiver_detail' => Yii::t('app', 'Receiver Detail'),
            'receiver_type' => Yii::t('app', 'Receiver Type'),
            'message' => Yii::t('app', 'Email Body'),
            'header_info' => Yii::t('app', 'Email Subject'),
            'status' => Yii::t('app', 'Status'),
            'send_status' => Yii::t('app', 'Send Status'),
            'content_id' => Yii::t('app', 'Content ID'),
            'refecence_code' => Yii::t('app', 'Refecence Code'),
            'module_type' => Yii::t('app', 'Module Type'),
            'entry_datetime' => Yii::t('app', 'Entry Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'mail_receiver_detail' => Yii::t('app', 'Receiver Email'),
        ];
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['id' => 'receiver_detail']);
    }

}
