<?php

namespace app\modules\webservice\models;

use Yii;
use app\modules\restservices\models\TblAppActivation;

/**
 * This is the model class for table "tbl_app_notification".
 *
 * @property integer $notification_id
 * @property string $notification_text
 * @property string $mobile_no
 * @property boolean $is_send
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property boolean $is_active
 * @property boolean $is_delete
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $flg_sentbox_entry
 * @property string $notification_title
 */
class TblAppNotification extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_app_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['notification_text'], 'string'],
            [['is_send', 'is_active', 'is_delete'], 'boolean'],
            [['created_at', 'updated_at', 'sync_timestamp', 'device_count', 'activation_id', 'success_count'], 'safe'],
            [['mobile_no'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['sync_status', 'flg_sentbox_entry'], 'string', 'max' => 1],
            [['notification_title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'notification_id' => Yii::t('app', 'Notification ID'),
            'notification_text' => Yii::t('app', 'Notification Text'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'is_send' => Yii::t('app', 'Is Send'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'notification_title' => Yii::t('app', 'Notification Title'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblAppNotificationQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblAppNotificationQuery(get_called_class());
    }

    public function getActiveMobile() {
        $this->mobile_no = \Yii::$app->general->encryptData($this->mobile_no);
        return $this->hasMany(TblAppActivation::className(), ['mobile_no' => 'mobile_no'])->where([ 'is_active' => 1, 'is_delete' => 0, 'is_expired' => 0]);
    }

    public function getRecord() {
        return $this->find()->where(['is_send' => 0])->all();
    }

}
