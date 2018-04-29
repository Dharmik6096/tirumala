<?php

namespace app\modules\notification\models;

use Yii;

/**
 * This is the model class for table "tbl_notifications_history".
 *
 * @property integer $id
 * @property integer $notification_id
 * @property string $title
 * @property string $msg
 * @property resource $msg_by
 * @property resource $attachment
 * @property string $publish_date
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 */
class TblNotificationsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_notifications_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notification_id', 'is_active'], 'safe'],
            [['type','title', 'msg', 'msg_by', 'attachment', 'created_by', 'updated_by', 'operation_type'], 'safe'],
            [['publish_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'notification_id' => Yii::t('app', 'Notification ID'),
            'title' => Yii::t('app', 'Title'),
            'msg' => Yii::t('app', 'Message'),
            'msg_by' => Yii::t('app', 'Message By'),
            'attachment' => Yii::t('app', 'Attachment'),
            'publish_date' => Yii::t('app', 'Publish Date'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),            
        ];
    }

    /**
     * @inheritdoc
     * @return TblNotificationsHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblNotificationsHistoryQuery(get_called_class());
    }
}
