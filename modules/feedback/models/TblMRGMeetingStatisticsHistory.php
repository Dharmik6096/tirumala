<?php

namespace app\modules\feedback\models;

use Yii;

/**
 * This is the model class for table "tbl_MRG_meeting_statistics_history".
 *
 * @property integer $MRG_M_statistics_id
 * @property integer $MRG_M_Id
 * @property string $from_date
 * @property string $to_date
 * @property integer $statistic_que_Id
 * @property string $answers
 * @property string $system_numbers
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
class TblMRGMeetingStatisticsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_MRG_meeting_statistics_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['MRG_M_Id', 'from_date', 'to_date', 'statistic_que_Id', 'answers', 'system_numbers', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry', 'originating_type', 'history_created_at', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'MRG_M_statistics_id' => Yii::t('app', 'Mrg M Statistics ID'),
            'MRG_M_Id' => Yii::t('app', 'Mrg M ID'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'statistic_que_Id' => Yii::t('app', 'Statistic Que ID'),
            'answers' => Yii::t('app', 'Answers'),
            'system_numbers' => Yii::t('app', 'System Numbers'),
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
