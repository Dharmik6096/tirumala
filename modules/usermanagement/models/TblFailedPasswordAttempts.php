<?php

namespace app\modules\usermanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_failed_password_attempts".
 *
 * @property integer $log_id
 * @property string $user_code
 * @property string $attempt_timestamp
 * @property string $ip_address
 * @property string $attempt_status
 * @property integer $remaining_attempts
 * @property integer $suspension_status
 * @property string $suspension_until
 * @property string $user_agent
 * @property string $created_at
 */
class TblFailedPasswordAttempts extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_failed_password_attempts';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_code', 'attempt_timestamp', 'ip_address', 'attempt_status', 'remaining_attempts', 'suspension_status', 'suspension_until', 'user_agent', 'created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'user_code' => Yii::t('app', 'User Code'),
            'attempt_timestamp' => Yii::t('app', 'Attempt Timestamp'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'attempt_status' => Yii::t('app', 'Attempt Status'),
            'remaining_attempts' => Yii::t('app', 'Remaining Attempts'),
            'suspension_status' => Yii::t('app', 'Suspension Status'),
            'suspension_until' => Yii::t('app', 'Suspension Until'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    public function saveFailedPasswordAttempts($user, $attempt_status = 'failed') {
        $this->user_code = $user->user_code;
        $this->attempt_timestamp = $this->created_at = date('Y-m-d H:i:s');
        $this->ip_address = Yii::$app->request->userIP;
        $this->attempt_status = $attempt_status;
        $this->remaining_attempts = $user->max_login_attempts;
        $this->suspension_status = !empty($user->suspension_datetime) ? 1 : 0;
        $this->suspension_until = $user->suspension_datetime;
        $this->user_agent = Yii::$app->request->userAgent;
        $this->save();
    }

}
