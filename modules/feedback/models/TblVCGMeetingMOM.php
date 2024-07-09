<?php

namespace app\modules\feedback\models;

use Yii;
use app\models\ChildModel;
use app\modules\dcsoperation\models\TblMember;

/**
 * This is the model class for table "tbl_VCG_meeting_MOM".
 *
 * @property integer $VCG_MOM_id
 * @property integer $VCG_M_Id
 * @property string $month
 * @property string $concern
 * @property string $member_code
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
 */
class TblVCGMeetingMOM extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_VCG_meeting_MOM';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['VCG_M_Id', 'month', 'concern', 'member_code', 'mom_type_id', 'discussions', 'status', 'remarks_actions', 'process_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry', 'originating_type'], 'safe'],
            [['status'],'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'VCG_MOM_id' => Yii::t('app', 'Vcg Mom ID'),
            'VCG_M_Id' => Yii::t('app', 'Vcg M ID'),
            'month' => Yii::t('app', 'Month'),
            'concern' => Yii::t('app', 'Concern'),
            'member_code' => Yii::t('app', 'Member Code'),
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
        ];
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

}
