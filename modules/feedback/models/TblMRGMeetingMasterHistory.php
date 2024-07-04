<?php

namespace app\modules\feedback\models;

use Yii;

/**
 * This is the model class for table "tbl_MRG_meeting_master_history".
 *
 * @property integer $MRG_M_Id
 * @property string $MRG_M_code
 * @property string $MRG_date
 * @property string $from_time
 * @property string $to_time
 * @property string $m_from_date
 * @property string $m_to_date
 * @property integer $attandance_count
 * @property string $pib_office_code
 * @property string $area_office_code
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMRGMeetingMasterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_MRG_meeting_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['MRG_M_code', 'MRG_date', 'from_time', 'to_time', 'm_from_date', 'm_to_date', 'attandance_count', 'pib_office_code', 'area_office_code', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type', 'history_created_at', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'MRG_M_Id' => Yii::t('app', 'Mrg M ID'),
            'MRG_M_code' => Yii::t('app', 'Mrg M Code'),
            'MRG_date' => Yii::t('app', 'Mrg Date'),
            'from_time' => Yii::t('app', 'From Time'),
            'to_time' => Yii::t('app', 'To Time'),
            'm_from_date' => Yii::t('app', 'M From Date'),
            'm_to_date' => Yii::t('app', 'M To Date'),
            'attandance_count' => Yii::t('app', 'Attandance Count'),
            'pib_office_code' => Yii::t('app', 'Pib Office Code'),
            'area_office_code' => Yii::t('app', 'Area Office Code'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
