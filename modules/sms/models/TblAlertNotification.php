<?php

namespace app\modules\sms\models;

use Yii;

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
 * @property string $content_id
 * @property string $refecence_code
 * @property string $module_type
 * @property int $language_code
 * @property string $entry_datetime
 * @property string $pick_datetime
 * @property string $response_datetime
 */
class TblAlertNotification extends \app\models\ChildModel {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tbl_alert_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['receiver_detail', 'receiver_type', 'message', 'header_info', 'status', 'send_status', 'response_status', 'refecence_code', 'module_type', 'template_id'], 'safe'],
            [['language_code'], 'safe'],
            [['entry_datetime', 'pick_datetime', 'response_datetime', 'content_id', 'send_mail', 'created_by', 'activity_type'], 'safe'],
            [['send_mail'], 'default', 'value' => 0],
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
            'message' => Yii::t('app', 'Message'),
            'header_info' => Yii::t('app', 'Header Info'),
            'status' => Yii::t('app', 'Status'),
            'send_status' => Yii::t('app', 'Send Status'),
            'response_status' => Yii::t('app', 'Response Status'),
            'content_id' => Yii::t('app', 'Content ID'),
            'refecence_code' => Yii::t('app', 'Refecence Code'),
            'module_type' => Yii::t('app', 'Module Type'),
            'language_code' => Yii::t('app', 'Language Code'),
            'entry_datetime' => Yii::t('app', 'Entry Datetime'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
        ];
    }

    public function getData() {
        $to_date = date('Y-m-d H:i:s', strtotime('+1 day'));
        $from_date = date('Y-m-d H:i:s', strtotime('-1 day'));

        return $this->find()
//                        ->Where(['or', ['send_status' => 0], ['send_status' => NULL]])
//                        ->andWhere(['>=', 'entry_datetime', $from_date])
//                        ->andWhere(['<=', 'entry_datetime', $to_date])
                        ->andWhere(['alert_notification_id' => '113838'])
//                        ->limit(50)
//                        ->orderby('entry_datetime DESC')
                        ->all();
    }

    public function updateSmsStatus($value) {
        return $this->updateAll(['send_status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['alert_notification_id' => $value]);
    }

    public function getApiMasterCode() {
        return $this->hasOne(TblApiMaster::className(), ['api_master_id' => 'content_id']);
    }

    public function getAppAlertInfo($data) {
        return $query = $this->find()
                ->where(['receiver_detail' => $data->device_id, 'receiver_type' => 'APP_NOTIFICATION'])
                ->limit(15)
                ->orderby('entry_datetime DESC')
                ->all();
    }

}
