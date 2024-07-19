<?php

namespace app\modules\feedback\models;

use Yii;

/**
 * This is the model class for table "tbl_VCG_meeting_feedback_history".
 *
 * @property integer $VCG_M_feedback_id
 * @property integer $VCG_M_Id
 * @property string $month
 * @property string $feedback_desc
 * @property string $member_code
 * @property string $type
 * @property string $status
 * @property string $action_taken
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $flg_sentbox_entry
 * @property integer $originating_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblVCGMeetingFeedbackHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_VCG_meeting_feedback_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['VCG_M_feedback_id', 'VCG_M_Id', 'month', 'feedback_desc', 'member_code', 'type', 'status', 'action_taken', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry', 'originating_type', 'history_created_at', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'VCG_M_feedback_id' => Yii::t('app', 'Vcg M Feedback ID'),
            'VCG_M_Id' => Yii::t('app', 'Vcg M ID'),
            'month' => Yii::t('app', 'Month'),
            'feedback_desc' => Yii::t('app', 'Feedback Desc'),
            'member_code' => Yii::t('app', 'Member Code'),
            'type' => Yii::t('app', 'Type'),
            'status' => Yii::t('app', 'Status'),
            'action_taken' => Yii::t('app', 'Action Taken'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
