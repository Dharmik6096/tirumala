<?php

namespace app\modules\feedback\models;

use Yii;
use app\models\ChildModel;
use app\modules\dcsoperation\models\TblMember;
use app\modules\document\models\TblAttachment;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_MRG_meeting_attandance".
 *
 * @property integer $MRG_M_attandance_id
 * @property integer $MRG_M_Id
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $dcs_code
 * @property string $member_code
 * @property integer $is_present
 * @property integer $attachment_code
 * @property string $reason
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMRGMeetingAttandance extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_MRG_meeting_attandance';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['MRG_M_Id', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'member_code', 'is_present', 'attachment_code', 'reason', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'MRG_M_attandance_id' => Yii::t('app', 'Mrg M Attandance ID'),
            'MRG_M_Id' => Yii::t('app', 'Mrg M ID'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'route_code' => Yii::t('app', 'Route'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'member_code' => Yii::t('app', 'Member'),
            'is_present' => Yii::t('app', 'Is Present'),
            'attachment_code' => Yii::t('app', 'Attachment Code'),
            'reason' => Yii::t('app', 'Reason'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getAttachmentCode() {
        return $this->hasOne(TblAttachment::className(), ['module_code' => 'MRG_M_attandance_id']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
}
