<?php

namespace app\modules\notification\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_notifications".
 *
 * @property integer $id
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
 */
class TblNotifications extends ChildModel {
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type','title', 'msg', 'msg_by'], 'required'],
            [['title', 'msg', 'msg_by'], 'string'],
            [['publish_date', 'created_at', 'updated_at', 'created_by', 'updated_by', 'type'], 'safe'],
            [['is_active'], 'integer'],
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
        ];
    }

    /**
     * @inheritdoc
     * @return TblNotificationsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblNotificationsQuery(get_called_class());
    }
}
