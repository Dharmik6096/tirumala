<?php

namespace app\modules\webservice\eipl\models;

use Yii;

/**
 * This is the model class for table "tbl_user_app_scheduler".
 *
 * @property integer $user_app_scheduler_id
 * @property string $user_type
 * @property integer $allow_scheduler
 * @property integer $interval
 * @property string $device_id
 * @property string $m_start_time
 * @property string $m_end_time
 * @property string $e_start_time
 * @property string $e_end_time
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblUserAppScheduler extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_user_app_scheduler';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_type', 'device_id', 'created_by', 'updated_by'], 'string'],
            [['allow_scheduler', 'interval'], 'integer'],
            [['m_start_time', 'm_end_time', 'e_start_time', 'e_end_time', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_app_scheduler_id' => Yii::t('app', 'User App Scheduler ID'),
            'user_type' => Yii::t('app', 'User Type'),
            'allow_scheduler' => Yii::t('app', 'Allow Scheduler'),
            'interval' => Yii::t('app', 'Interval'),
            'device_id' => Yii::t('app', 'Device ID'),
            'm_start_time' => Yii::t('app', 'M Start Time'),
            'm_end_time' => Yii::t('app', 'M End Time'),
            'e_start_time' => Yii::t('app', 'E Start Time'),
            'e_end_time' => Yii::t('app', 'E End Time'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getRecord() {
        return $this->find()
                        ->where(['user_type' => $this->user_type])
                        ->one();
    }

}
