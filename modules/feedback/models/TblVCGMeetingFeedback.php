<?php

namespace app\modules\feedback\models;

use Yii;
use app\models\ChildModel;
use app\modules\dcsoperation\models\TblMember;

/**
 * This is the model class for table "tbl_VCG_meeting_feedback".
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
 */
class TblVCGMeetingFeedback extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_VCG_meeting_feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['VCG_M_Id', 'month', 'feedback_desc', 'member_code', 'type', 'status', 'action_taken', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry', 'originating_type'], 'safe'],
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
        ];
    }
    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }
}
