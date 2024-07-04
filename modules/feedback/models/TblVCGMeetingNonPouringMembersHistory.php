<?php

namespace app\modules\feedback\models;

use Yii;

/**
 * This is the model class for table "tbl_VCG_meeting_non_pouring_members_history".
 *
 * @property integer $VCG_meeting_non_pouring_id
 * @property integer $VCG_M_Id
 * @property string $month
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $member_code
 * @property integer $reason_id
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
class TblVCGMeetingNonPouringMembersHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_VCG_meeting_non_pouring_members_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['VCG_meeting_non_pouring_id', 'VCG_M_Id', 'month', 'statistic_que_Id', 'answers', 'system_numbers', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry', 'originating_type', 'history_created_at', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'VCG_meeting_non_pouring_id' => Yii::t('app', 'Vcg Meeting Non Pouring ID'),
            'VCG_M_Id' => Yii::t('app', 'Vcg M ID'),
            'month' => Yii::t('app', 'Month'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'reason_id' => Yii::t('app', 'Reason ID'),
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
