<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_member_share_deposit_history".
 *
 * @property integer $id
 * @property integer $member_share_deposit_id
 * @property string $member_code
 * @property integer $allotted_share
 * @property integer $proposed_share
 * @property string $total_share
 * @property string $share_amount
 * @property string $till_date
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblMemberShareDepositHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_share_deposit_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_code', 'total_share', 'folio_no', 'share_amount', 'member_share_deposit_id', 'operation_type', 'history_created_by', 'originating_org_code', 'originating_org_type', 'updated_by', 'created_by', 'allotted_share', 'till_date', 'updated_at', 'created_at', 'history_created_at', 'proposed_share', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_share_deposit_id' => Yii::t('app', 'Member Share Deposit ID'),
            'member_code' => Yii::t('app', 'Member Code'),
            'allotted_share' => Yii::t('app', 'Allotted Share'),
            'proposed_share' => Yii::t('app', 'Proposed Share'),
            'total_share' => Yii::t('app', 'Total Share'),
            'share_amount' => Yii::t('app', 'Share Amount'),
            'till_date' => Yii::t('app', 'Till Date'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }

}
