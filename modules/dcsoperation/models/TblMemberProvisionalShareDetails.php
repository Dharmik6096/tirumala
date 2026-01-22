<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\models\ChildModel;
use app\modules\dcsoperation\models\TblUnionShareConfig;

/**
 * This is the model class for table "tbl_member_provisional_share_details".
 *
 * @property integer $member_provisional_share_detail_code
 * @property string $union_code
 * @property string $provisional_member_code
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
class TblMemberProvisionalShareDetails extends ChildModel {

    public $gender_code, $bmc_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_provisional_share_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['amount_deposit', 'payable_share_amount', 'admission_fee', 'amount_payable', 'total_amount', 'balance_amount', 'admission_fee_recovery', 'deposit_date', 'created_at', 'updated_at', 'no_of_share_req', 'no_of_share_apply', 'originating_type', 'union_code', 'provisional_member_code', 'mode_of_payment', 'bank_name', 'ref_no', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'per_share_rate', 'gender_code', 'bmc_code'], 'safe'],
                [['balance_amount', 'admission_fee_recovery'], 'default', 'value' => 0],
                [['payable_share_amount', 'admission_fee', 'amount_payable', 'total_amount', 'no_of_share_req', 'no_of_share_apply'], 'required', 'except' => ['import_receipt_detail', 'share_detail']],
                [['no_of_share_apply'], 'validateMaxShare', 'except' => ['import_receipt_detail', 'share_detail']],
                [['bank_name', 'deposit_date', 'mode_of_payment', 'amount_payable'], 'required', 'on' => ['import_receipt_detail']]
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblMemberProvisionalShareDetails', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_provisional_share_detail_code' => Yii::t('app', 'Member Provisional Share Detail Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'provisional_member_code' => Yii::t('app', 'Provisional Member Code'),
            'mode_of_payment' => Yii::t('app', 'Mode Of Payment'),
            'ref_no' => Yii::t('app', 'Receipt Reference No.'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'amount_deposit' => Yii::t('app', 'Amount Deposit'),
            'deposit_date' => Yii::t('app', 'Deposit Date'),
            'no_of_share_req' => Yii::t('app', 'No Of Shares Require'),
            'no_of_share_apply' => Yii::t('app', 'No Of Shares Apply'),
            'payable_share_amount' => Yii::t('app', 'Share Amount Payable'),
            'admission_fee' => Yii::t('app', 'Admission Fees'),
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

    public function getMemberShare() {
        return $this->find()->where(['provisional_member_code' => $this->provisional_member_code])->one();
        return $this->find()->where(['provisional_member_code' => $this->provisional_member_code])->one();
    }

    public function getShareData($pro_member_code) {
        return $this->find()->where(['provisional_member_code' => $pro_member_code])->one();
    }

    public function validateMaxShare($attribute, $params) {
        $maxShare = TblUnionShareConfig::find()->select('max_share')
                ->where(['process_type' => 'member', 'gender_code' => $this->gender_code, 'bmc_code' => $this->bmc_code])
                ->one();
        if (empty($maxShare)) {
            $maxShare = TblUnionShareConfig::find()->select('max_share')
                    ->where(['process_type' => 'member', 'gender_code' => $this->gender_code, 'union_code' => $this->union_code, 'bmc_code' => null])
                    ->one();
        }
        if (empty($maxShare)) {
            $this->addError($attribute, 'BMC Share Master Data Not Available');
        } else if ($this->no_of_share_apply > $maxShare['max_share']) {
            $this->addError($attribute, 'The number of shares applied can not be greter than maximum share limit.');
        }
    }

}
