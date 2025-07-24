<?php

namespace app\modules\feedback\models;

use Yii;

/**
 * This is the model class for table "tbl_VCG_MRG_member_history".
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
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblVCGMRGMemberHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_VCG_MRG_member_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['VCG_MRG_member_id', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'member_code', 'member_tr_code', 'wef_date', 'end_date', 'status', 'type', 'attachment_sign_key', 'attachment_photo_key', 'remark', 'approved_at', 'approved_by', 'transaction_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type', 'history_created_at', 'history_created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
