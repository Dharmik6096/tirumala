<?php

namespace app\modules\feedback\models;

use Yii;
use app\models\ChildModel;
use app\modules\dcsoperation\models\TblMember;
use app\modules\document\models\TblAttachment;

/**
 * This is the model class for table "tbl_VCG_meeting_attandance".
 *
 * @property integer $VCG_M_attandance_id
 * @property integer $VCG_M_Id
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $member_code
 * @property integer $is_present
 * @property integer $attachment_code
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblVCGMeetingAttandance extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_VCG_meeting_attandance';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['VCG_M_Id', 'mcc_plant_code', 'bmc_code', 'member_code', 'is_present', 'attachment_code', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'VCG_M_attandance_id' => Yii::t('app', 'Vcg M Attandance ID'),
            'VCG_M_Id' => Yii::t('app', 'Vcg M ID'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'is_present' => Yii::t('app', 'Is Present'),
            'attachment_code' => Yii::t('app', 'Attachment Code'),
            'remarks' => Yii::t('app', 'Remarks'),
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
        $this->VCG_M_attandance_id = (string) $this->VCG_M_attandance_id;
        return $this->hasOne(TblAttachment::className(), ['module_code' => 'VCG_M_attandance_id']);
    }

}
