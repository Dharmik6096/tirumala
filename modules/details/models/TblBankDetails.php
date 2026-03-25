<?php

namespace app\modules\details\models;

use Yii;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBranch;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_bank_details".
 *
 * @property integer $detail_code
 * @property string $module_name
 * @property string $module_code
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $is_default
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 * @property TblBanks $bankCode
 * @property TblBranch $branchCode
 */
class TblBankDetails extends \app\models\ChildModel {

    public $reference_id, $name_at_bank, $bank_name, $city, $branch, $micr, $name_match_result, $name_match_score, $account_status, $account_status_code, $utr, $ifsc_code, $has_available_branch_info, $branch_address, $branch_name, $beneficiary_id;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bank_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['branch_code', 'bank_account_no', 'ifsc'], 'required', 'on' => 'bank_selected', 'except' => 'deactivate'],
                [['branch_code', 'bank_account_no', 'ifsc', 'bank_code'], 'required', 'on' => 'additional'],
            /* [['branch_code', 'bank_account_no', 'ifsc'], 'required','when' => function($model) {
              return !empty($this->bank_code)?true:false;
              }, 'whenClient' => "function (attribute, value) { return $('#tblbankdetails-bank_code').val()!==''}"], */
                [['detail_code'], 'integer'],
                [['module_name', 'module_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'created_by', 'updated_by'], 'string'],
                [['created_at', 'updated_at', 'is_default', 'is_active', 'beneficiary_name', 'is_verified', 'remarks', 'reference_id', 'name_at_bank', 'bank_name', 'city', 'branch', 'micr', 'name_match_result', 'name_match_score', 'account_status', 'account_status_code', 'utr', 'ifsc_code', 'has_available_branch_info', 'branch_address', 'branch_name', 'beneficiary_id'], 'safe'],
//            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function($model) {
//            return $model->module_name == $this->module_name;
//        }],
//            [['bank_account_no'], 'CheckDuplicate'],
            [['ifsc'], function ($attribute, $params) {
                    Yii::$app->general->validateIfsc($this, $attribute, $params);
                }, 'skipOnEmpty' => true, 'except' => ['verification', 'kycVerify']],
                [['bank_account_no'], function ($attribute, $params) {
                    $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
                    if ($error !== TRUE)
                        $this->addError($attribute, $error);
                }, 'except' => ['verification', 'kycVerify']],
                [['bank_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBanks::className(), 'targetAttribute' => ['bank_code' => 'bank_code'], 'except' => ['verification', 'kycVerify']],
                [['branch_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBranch::className(), 'targetAttribute' => ['branch_code' => 'branch_code'], 'except' => ['verification', 'kycVerify']],
                [['beneficiary_name'], function ($attribute, $params) {
                    $error = Yii::$app->general->validateBeneficiary($this, $attribute, $params);
                    if ($error != NULL) {
                        $this->addError($attribute, Yii::t('app/validation', 'Beneficiary Name Is Invalid'));
                    }
                }, 'skipOnEmpty' => false, 'except' => ['verification', 'kycVerify']],
                [['branch_code', 'bank_account_no', 'ifsc', 'bank_code'], 'required', 'on' => 'main_create'],
                [['is_verified'], 'default', 'value' => 0],
                [['is_kyc_verified'], 'default', 'value' => 0],
                [['ifsc', 'bank_account_no', 'bank_code', 'branch_code', 'beneficiary_name'], 'required', 'on' => 'kycVerify'],
        ];

        $client_rules = Yii::$app->customvalidation->getRules('TblBankDetails', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'detail_code' => Yii::t('app', 'Detail Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'module_code' => Yii::t('app', 'Module Code'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'is_default' => Yii::t('app', 'Is Default'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'adhar_no' => Yii::t('app', 'Aadhaar No.'),
            'is_kyc_verified' => Yii::t('app', 'Is Kyc Verified'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankCode() {
        return $this->hasOne(TblBanks::className(), ['bank_code' => 'bank_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranchCode() {
        return $this->hasOne(TblBranch::className(), ['branch_code' => 'branch_code']);
    }

    /**
     * @inheritdoc
     * @return TblBankDetailsQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblBankDetailsQuery(get_called_class());
    }

    public function setModel($module, $module_code, $default = 1) {
        $this->module_name = $module;
        $this->module_code = $module_code;
        $this->detail_code = Yii::$app->general->getCodeAutoIncrement($this);
        $this->is_active = 1;
        $this->is_default = $default;
    }

    public function CheckDuplicate($attribute, $param) {
        if (!empty($this->bank_account_no)) {
            $data = $this->find()->where(['or', ['bank_account_no' => $this->bank_account_no], ['bank_account_no' => \Yii::$app->general->encryptData($this->bank_account_no)]])
                            ->andWhere(['or', ['ifsc' => $this->ifsc], ['ifsc' => \Yii::$app->general->encryptData($this->ifsc)]])
                            ->andWhere(['<>', 'detail_code', $this->detail_code])
                            ->andWhere(['is_active' => 1])->one();
            if (!empty($data)) {
                $this->addError($attribute, Yii::t('app/validation', 'Bank Account No has already been taken.'));
            }
        }
    }

    public function getUniqueBankDetails() {
        return $this->find()->where(['or', ['bank_account_no' => $this->bank_account_no], ['bank_account_no' => \Yii::$app->general->encryptData($this->bank_account_no)]])
                        ->andWhere(['or', ['ifsc' => $this->ifsc], ['ifsc' => \Yii::$app->general->encryptData($this->ifsc)]])
                        ->andWhere(['<>', 'detail_code', $this->detail_code])
                        ->andWhere(['is_active' => 1])
                        ->andWhere(['module_name' => $this->module_name])
                        ->one();
    }

    public function getBankDetails() {
        return $this->find()
                        ->where(['is_active' => 1, 'ifsc' => $this->ifsc, 'bank_account_no' => $this->bank_account_no])
                        ->one();
    }

    public function getDcsDetail() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'module_code']);
    }

    public function getCustomerDetail() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'module_code']);
    }

    public static function updateBankDetails($code, $bank_account_no, &$mapList) {
        $activeBanks = self::find()
            ->where(['module_name' => 'society', 'module_code' => $code, 'is_active' => 1])
            ->indexBy('bank_account_no')
            ->all();

        $currentDefaultBank = false;
        foreach ($activeBanks as $b) {
            if ($b->is_default == 1) {
                $currentDefaultBank = $b;
                break;
            }
        }

        $bankDetails = null;
        if (isset($activeBanks[$bank_account_no])) {
            $bankDetails = $activeBanks[$bank_account_no];
            if ($bankDetails->is_default != 1) {
                $historyBankDetails = new TblBankDetailsHistory();
                \Yii::$app->operation->history($bankDetails, $historyBankDetails, UPDATE);
                $mapList[] = $historyBankDetails;
            }
        } else {
            $bankDetails = new self();
        }

        if ($currentDefaultBank && $currentDefaultBank->detail_code != $bankDetails->detail_code) {
            $historyOldDefault = new TblBankDetailsHistory();
            \Yii::$app->operation->history($currentDefaultBank, $historyOldDefault, UPDATE);
            $mapList[] = $historyOldDefault;
            $currentDefaultBank->is_active = 0;
            $currentDefaultBank->is_default = 0;
            $mapList[] = $currentDefaultBank;
        }

        $bankDetails->is_default = 1;
        $bankDetails->is_active = 1;

        return $bankDetails;
    }

}
