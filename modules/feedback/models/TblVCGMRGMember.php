<?php

namespace app\modules\feedback\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_VCG_MRG_member".
 *
 * @property integer $VCG_MRG_member_id
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $dcs_code
 * @property string $member_code
 * @property string $member_tr_code
 * @property string $wef_date
 * @property string $end_date
 * @property string $status
 * @property string $type
 * @property string $attachment_sign_key
 * @property string $attachment_photo_key
 * @property string $remark
 * @property string $approved_at
 * @property string $approved_by
 * @property string $transaction_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblVCGMRGMember extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_VCG_MRG_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wef_date', 'end_date', 'approved_at', 'transaction_date', 'created_at', 'updated_at', 'originating_type', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'type', 'member_code', 'approved_by', 'originating_org_code', 'originating_org_type', 'member_tr_code', 'attachment_sign_key', 'attachment_photo_key', 'status', 'remark', 'created_by', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'VCG_MRG_member_id' => Yii::t('app', 'Vcg Mrg Member ID'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'member_tr_code' => Yii::t('app', 'Member Tr Code'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'status' => Yii::t('app', 'Status'),
            'type' => Yii::t('app', 'Type'),
            'attachment_sign_key' => Yii::t('app', 'Attachment Sign Key'),
            'attachment_photo_key' => Yii::t('app', 'Attachment Photo Key'),
            'remark' => Yii::t('app', 'Remark'),
            'approved_at' => Yii::t('app', 'Approved At'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'transaction_date' => Yii::t('app', 'Transaction Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }
}
