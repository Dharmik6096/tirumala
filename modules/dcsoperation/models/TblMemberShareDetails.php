<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_member_share_details".
 *
 * @property integer $member_share_detail_code
 * @property string $union_code
 * @property string $member_code
 * @property string $mode_of_payment
 * @property string $ref_no
 * @property string $bank_name
 * @property string $amount_deposit
 * @property string $deposit_date
 * @property integer $no_of_share_req
 * @property integer $no_of_share_apply
 * @property string $payable_share_amount
 * @property string $admission_fee
 * @property string $amount_payable
 * @property string $total_amount
 * @property string $balance_amount
 * @property string $admission_fee_recovery
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMemberShareDetails extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_share_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['amount_deposit', 'payable_share_amount', 'admission_fee', 'amount_payable', 'total_amount', 'balance_amount', 'admission_fee_recovery', 'deposit_date', 'created_at', 'updated_at', 'no_of_share_req', 'no_of_share_apply', 'originating_type', 'union_code', 'member_code', 'mode_of_payment', 'bank_name', 'ref_no', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_share_detail_code' => Yii::t('app', 'Member Share Detail Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'mode_of_payment' => Yii::t('app', 'Mode Of Payment'),
            'ref_no' => Yii::t('app', 'Ref No'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'amount_deposit' => Yii::t('app', 'Amount Deposit'),
            'deposit_date' => Yii::t('app', 'Deposit Date'),
            'no_of_share_req' => Yii::t('app', 'No Of Share Req'),
            'no_of_share_apply' => Yii::t('app', 'No Of Share Apply'),
            'payable_share_amount' => Yii::t('app', 'Payable Share Amount'),
            'admission_fee' => Yii::t('app', 'Admission Fee'),
            'amount_payable' => Yii::t('app', 'Amount Payable'),
            'total_amount' => Yii::t('app', 'Total Amount'),
            'balance_amount' => Yii::t('app', 'Balance Amount'),
            'admission_fee_recovery' => Yii::t('app', 'Admission Fee Recovery'),
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
