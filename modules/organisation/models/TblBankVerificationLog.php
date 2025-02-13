<?php

namespace app\modules\organisation\models;

use Yii;
class TblBankVerificationLog extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bank_verification_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','member_code','customer_code','code','name','type','bank_name','branch_name','bank_account_no','ifsc','beneficiary_name','aadhar_no','resp_reference_id','resp_city','resp_micr','resp_name_at_bank','resp_bank_name','resp_branch','resp_bank_account_no','resp_ifsc','resp_name_match_result','resp_name_match_score','resp_account_status','resp_account_status_code','is_kyc_verified','resp_utr','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bank_verification_log_code' => Yii::t('app', 'Bank Verification Log Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant'),
            'bmc_code' => Yii::t('app', 'Bmc'),
            'dcs_code' => Yii::t('app', 'Dcs'),
            'member_code' => Yii::t('app', 'Member'),
            'customer_code' => Yii::t('app', 'Customer'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'aadhar_no' => Yii::t('app', 'Aadhar No'),
            'resp_reference_id' => Yii::t('app', 'Resp Reference Id'),
            'resp_city' => Yii::t('app', 'Resp City'),
            'resp_micr' => Yii::t('app', 'Resp MICR'),
            'resp_name_at_bank' => Yii::t('app', 'Resp Name At Bank'),
            'resp_bank_name' => Yii::t('app', 'Resp Bank Name'),
            'resp_branch' => Yii::t('app', 'Resp Branch'),
            'resp_bank_account_no' => Yii::t('app', 'Resp Bank Account No'),
            'resp_ifsc' => Yii::t('app', 'Resp IFSC'),
            'resp_name_match_result' => Yii::t('app', 'Resp Name Match Result'),
            'resp_name_match_score' => Yii::t('app', 'Resp Name Match Score'),
            'resp_account_status' => Yii::t('app', 'Resp Account Status'),
            'resp_account_status_code' => Yii::t('app', 'Resp Account Status Code'),
            'is_kyc_verified' => Yii::t('app', 'Is Kyc Verified'),
            'resp_utr' => Yii::t('app', 'Resp UTR'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function setLogData($postData, $model, &$saveModel){
        if(!empty($postData)){
            $responseJson = json_decode($postData['responseJson']);
            $this->setAttributes($model->attributes);
            $this->code = $postData['code'];
            $this->type = $postData['type'];
            $this->bank_name = !empty($model->bankCode) ? Yii::$app->general->getforeignkey($model->bankCode, 'bank_name') : '';
            $this->branch_name = !empty($model->branchCode) ? Yii::$app->general->getforeignkey($model->branchCode, 'branch_name') : '';
            $this->beneficiary_name = !empty($model->mainBankDetails) ? Yii::$app->general->getforeignkey($model->mainBankDetails, 'beneficiary_name') : '';
            if (!empty($model->aadhaar_no)) {
                $this->aadhar_no = $model->aadhaar_no;
            } else if(!empty($model->adhar_no)) {
                $this->aadhar_no = $model->adhar_no;
            }
            $this->resp_reference_id = !empty($responseJson->reference_id) ? $responseJson->reference_id : null;
            $this->resp_city = !empty($responseJson->city) ? $responseJson->city : null;
            $this->resp_micr = !empty($responseJson->micr) ? $responseJson->micr : null;
            $this->resp_name_at_bank = !empty($responseJson->name_at_bank) ? $responseJson->name_at_bank : null;
            $this->resp_bank_name = !empty($responseJson->bank_name) ? $responseJson->bank_name : null;
            $this->resp_branch = !empty($responseJson->branch) ? $responseJson->branch : null;
            $this->resp_bank_account_no = !empty($responseJson->bank_account_no) ? $responseJson->bank_account_no : null;
            $this->resp_ifsc = !empty($responseJson->ifsc) ? $responseJson->ifsc : null;
            $this->resp_name_match_result = !empty($responseJson->name_match_result) ? $responseJson->name_match_result : null;
            $this->resp_name_match_score = !empty($responseJson->name_match_score) ? $responseJson->name_match_score : null;
            $this->resp_account_status = !empty($responseJson->account_status) ? $responseJson->account_status : null;
            $this->resp_account_status_code = !empty($responseJson->account_status_code) ? $responseJson->account_status_code : null;
            $this->resp_utr = !empty($responseJson->utr) ? $responseJson->utr : null;
            $saveModel[] = $this;
        }
    }

}