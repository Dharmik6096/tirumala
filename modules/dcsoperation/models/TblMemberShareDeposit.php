<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_member_share_deposit".
 *
 * @property integer $member_share_deposit_id
 * @property string $member_code
 * @property integer $allotted_share
 * @property string $proposed_share
 * @property string $total_share
 * @property string $till_date
 * @property string $share_amount
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 */
class TblMemberShareDeposit extends \app\models\ChildModel {

    public $import_eipl_code, $import_union_code, $import_key_pattern;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_share_deposit';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_code', 'folio_no', 'originating_type', 'updated_at', 'created_at', 'originating_org_code', 'originating_org_type', 'updated_by', 'created_by', 'allotted_share', 'proposed_share', 'total_share', 'share_amount', 'till_date'], 'safe'],
                [['till_date'], 'convertDate', 'on' => ['importCsv']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_share_deposit_id' => Yii::t('app', 'Member Share Deposit ID'),
            'member_code' => Yii::t('app', 'Member Code'),
            'allotted_share' => Yii::t('app', 'In RTA & Allotted Shares'),
            'proposed_share' => Yii::t('app', 'Proposed Shares'),
            'total_share' => Yii::t('app', 'Total Shares'),
            'share_amount' => Yii::t('app', 'Share Amount'),
            'till_date' => Yii::t('app', 'Till Date'),
            'folio_no' => Yii::t('app', 'Folio No.'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->till_date = !empty($this->till_date) ? Yii::$app->controls->view_date($this->till_date, 'php:Y-m-d') : NULL;
        }
    }

}
