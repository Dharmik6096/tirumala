<?php

namespace app\modules\feedback\models;

use Yii;

/**
 * This is the model class for table "tbl_MRG_meeting_MOM_history".
 *
 * @property integer $MRG_MOM_id
 * @property integer $MRG_M_Id
 * @property string $month
 * @property string $concern
 * @property string $dcs_code
 * @property integer $mom_type_id
 * @property string $discussions
 * @property integer $status
 * @property string $remarks_actions
 * @property string $process_type
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
class TblMRGMeetingMOMHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_MRG_meeting_MOM_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['MRG_M_Id', 'month', 'concern', 'dcs_code', 'mom_type_id', 'discussions', 'status', 'remarks_actions', 'process_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry', 'originating_type', 'history_created_at', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'MRG_MOM_id' => Yii::t('app', 'Mrg Mom ID'),
            'MRG_M_Id' => Yii::t('app', 'Mrg M ID'),
            'month' => Yii::t('app', 'Month'),
            'concern' => Yii::t('app', 'Concern'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'mom_type_id' => Yii::t('app', 'Mom Type ID'),
            'discussions' => Yii::t('app', 'Discussions'),
            'status' => Yii::t('app', 'Status'),
            'remarks_actions' => Yii::t('app', 'Remarks Actions'),
            'process_type' => Yii::t('app', 'Process Type'),
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
