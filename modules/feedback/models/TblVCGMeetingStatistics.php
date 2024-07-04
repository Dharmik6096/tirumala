<?php

namespace app\modules\feedback\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_VCG_meeting_statistics".
 *
 * @property integer $VCG_M_statistics_id
 * @property integer $VCG_M_Id
 * @property string $month
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
 */
class TblVCGMeetingStatistics extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_VCG_meeting_statistics';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['VCG_M_Id', 'month', 'statistic_que_Id', 'answers', 'system_numbers', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'VCG_M_statistics_id' => Yii::t('app', 'Vcg M Statistics ID'),
            'VCG_M_Id' => Yii::t('app', 'Vcg M ID'),
            'month' => Yii::t('app', 'Month'),
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
        ];
    }

}
