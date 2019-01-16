<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user_profile".
 *
 * @property string $id
 * @property string $user_id
 * @property integer $profile_id
 * @property string $flg_sentbox_entry
 * @property string $sync_status
 * @property string $sync_timestamp
 */
class TblUserProfile extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'required'],
            [['id', 'user_id', 'flg_sentbox_entry', 'sync_status'], 'string'],
            [['profile_id'], 'integer'],
            [['sync_timestamp'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'profile_id' => Yii::t('app', 'Profile ID'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
        ];
    }

}
