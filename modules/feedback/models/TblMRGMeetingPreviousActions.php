<?php

namespace app\modules\feedback\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_MRG_meeting_previous_actions".
 *
 * @property integer $MRG_previous_actions_id
 * @property integer $MRG_M_Id
 * @property integer $MRG_M_feedback_id
 * @property integer $MRG_M_MOM_id
 * @property string $description
 * @property string $type
 * @property integer $isclose
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $flg_sentbox_entry
 * @property integer $originating_type
 */
class TblMRGMeetingPreviousActions extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_MRG_meeting_previous_actions';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['MRG_M_Id', 'MRG_M_feedback_id', 'MRG_M_MOM_id', 'description', 'type', 'isclose', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry', 'originating_type'], 'safe'],
            [['isclose'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'MRG_previous_actions_id' => Yii::t('app', 'Mrg Previous Actions ID'),
            'MRG_M_Id' => Yii::t('app', 'Mrg M ID'),
            'MRG_M_feedback_id' => Yii::t('app', 'Mrg M Feedback ID'),
            'MRG_M_MOM_id' => Yii::t('app', 'Mrg M Mom ID'),
            'description' => Yii::t('app', 'Description'),
            'type' => Yii::t('app', 'Type'),
            'isclose' => Yii::t('app', 'Isclose'),
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

    public function getVCGMFeedbackId() {
        return $this->hasOne(TblMRGMeetingFeedback::className(), ['MRG_M_feedback_id' => 'MRG_M_feedback_id']);
    }
    public function getVCGMMonId() {
        return $this->hasOne(TblMRGMeetingMOM::className(), ['MRG_MOM_id' => 'MRG_M_MOM_id']);
    }

}
