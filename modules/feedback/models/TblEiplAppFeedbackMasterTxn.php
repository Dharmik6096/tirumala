<?php

namespace app\modules\feedback\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_app_feedback_master_txn".
 *
 * @property integer $eipl_app_feedback_master_txn_code
 * @property integer $eipl_app_feedback_master_code
 * @property string $feedback_message
 * @property string $feedback_message_datetime
 * @property string $name
 * @property string $originator_type
 * @property string $originator_code
 * @property string $replier_type
 * @property string $replier_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblEiplAppFeedbackMasterTxn extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_eipl_app_feedback_master_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['eipl_app_feedback_master_code'], 'integer'],
            [['feedback_message', 'name', 'originator_type', 'originator_code', 'replier_type', 'replier_code', 'created_by', 'updated_by'], 'safe'],
            [['eipl_app_feedback_master_code', 'feedback_message_datetime', 'created_at', 'updated_at', 'feedback_message', 'name', 'originator_type', 'originator_code', 'replier_type', 'replier_code', 'created_by', 'updated_by', 'file_code', 'file_name', 'file_path'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'eipl_app_feedback_master_txn_code' => Yii::t('app', 'Eipl App Feedback Master Txn Code'),
            'eipl_app_feedback_master_code' => Yii::t('app', 'Eipl App Feedback Master Code'),
            'feedback_message' => Yii::t('app', 'Feedback Message'),
            'feedback_message_datetime' => Yii::t('app', 'Feedback Message Datetime'),
            'name' => Yii::t('app', 'Name'),
            'originator_type' => Yii::t('app', 'Originator Type'),
            'originator_code' => Yii::t('app', 'Originator Code'),
            'replier_type' => Yii::t('app', 'Replier Type'),
            'replier_code' => Yii::t('app', 'Replier Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'file_code' => Yii::t('app', 'File Code'),
            'file_name' => Yii::t('app', 'File Name'),
            'file_path' => Yii::t('app', 'File Path'),
        ];
    }

}
