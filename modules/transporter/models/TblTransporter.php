<?php

namespace app\modules\transporter\models;

use Yii;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblHamlets;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBranch;
use app\modules\organisation\models\TblUnionsDistrictMapping;
use app\modules\transporter\models\TblBillingType;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblContactDetails;
use app\modules\general\models\TblDepartment;

/**
 * This is the model class for table "tbl_transporter".
 *
 * @property string $transporter_code
 * @property string $transporter_name
 * @property string $local_name
 * @property string $address
 * @property string $phone_no
 * @property string $mobile_no
 * @property string $email
 * @property string $pincode
 * @property string $registration_no
 * @property string $contact_person
 * @property string $local_contact_person
 * @property string $bank_code
 * @property string $branch_code
 * @property string $bank_account_no
 * @property string $ifsc
 * @property string $gstin
 * @property string $tds_per
 * @property string $pan_no
 * @property string $beneficiary_name
 * @property string $agreement_no
 * @property string $declaration
 * @property string $security_cheque_no
 * @property string $union_code
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 * @property string $security_amount
 */
class TblTransporter extends \app\models\ChildModel {

    public $middle_name, $local_middlename, $surname, $local_surname, $department;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transporter';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
                [['transporter_name', 'address', 'union_code', 'vendor_code', 'billing_type_code'], 'required', 'except' => ['importCsv', 'activation']],
                [['transporter_name', 'address', 'vendor_code', 'union_code', 'billing_type_code'], 'required', 'on' => 'importCsv'],
                [['transporter_name', 'local_name', 'address', 'phone_no', 'mobile_no', 'email', 'contact_person', 'local_contact_person', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'gstin', 'pan_no', 'beneficiary_name', 'agreement_no', 'declaration', 'security_cheque_no', 'union_code', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'created_by', 'updated_by'], 'string', 'except' => ['activation']],
                [['registration_no'], 'unique', 'skipOnEmpty' => true, 'except' => ['activation']],
                [['transporter_code'], 'required', 'except' => ['importCsv', 'activation']],
                [['vendor_code'], 'number', 'except' => ['activation']],
                [['transporter_code'], 'integer', 'except' => ['activation']],
                [['email'], 'email', 'except' => ['activation']],
                [['local_contact_person', 'local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['activation']],
                [['transporter_name', 'contact_person', 'beneficiary_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['activation']],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => true, 'except' => ['activation']],
                [['phone_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildatePhoneNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['activation']],
                [['registration_no'], 'string', 'max' => 20],
                [['created_at', 'updated_at', 'security_amount', 'middle_name', 'local_middlename', 'surname', 'local_surname', 'department', 'vendor_code', 'beneficiary_name', 'vendor_name', 'transporter_type', 'agreement_from_date', 'agreement_to_date', 'billing_type_code', 'ifsc'], 'safe'],
                ['bank_account_no', 'unique', 'when' => function($model) {
                    $data = $this->find()->where(['ifsc' => $model->ifsc])->one();
                    return ($data) ? true : false;
                }, 'skipOnEmpty' => true, /* 'targetAttribute' => 'bank_code' */ 'except' => ['activation']],
                ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['activation']],
                [['bank_account_no'], function ($attribute, $params) {
                    $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
                    if ($error !== TRUE)
                        $this->addError($attribute, $error);
                }, 'except' => ['activation']],
                [['pan_no', 'email', 'mobile_no'], 'unique', 'except' => ['activation']],
                [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['activation']],
                [['ifsc'], function ($attribute, $params) {
                    Yii::$app->general->validateIfsc($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'when' => function() {
                    return !empty($this->branch_code);
                }, 'except' => ['activation']],
                [['tds_per'], 'number', 'except' => ['activation']],
                [['security_amount', 'tds_per'], 'string', 'max' => 16, 'tooLong' => Yii::t('app/validation', '{attribute} should contain at most 16 digit '), 'skipOnEmpty' => true, 'except' => ['activation']],
                [['gstin'], 'string', 'max' => 11, 'skipOnEmpty' => true],
                [['agreement_no', 'declaration', 'security_cheque_no'], 'string', 'max' => 20, 'skipOnEmpty' => true, 'except' => ['activation']],
                [['is_active'], 'integer'],
                [['agreement_no', 'security_cheque_no', 'registration_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateNumericField($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['activation']],
                ['security_amount', 'number', 'min' => 1],
                [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code'], 'on' => ['importCsv']],
                [['department'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'department');
                }, 'on' => ['importCsv']],
                [['department'], 'exist', 'skipOnError' => true, 'targetClass' => TblDepartment::className(), 'targetAttribute' => ['department' => 'department_id'], 'on' => ['importCsv']],
                [['hamlet_code'], 'validateHamlet'],
                [['pan_no'], 'setPanNumber', 'on' => ['importCsv']],
//                [['branch_code'], function ($attribute, $params) {
//                    Yii::$app->general->validateBranch($this, $attribute, $params);
//                }, 'skipOnEmpty' => false, 'except' => ['activation']],
            [['hamlet_code'], 'validateHamlet', 'on' => 'importCsv'],
                [['agreement_to_date'], 'validateAgreeTo', 'except' => ['activation']],
                [['agreement_from_date', 'agreement_to_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01'), 'on' => 'importCsv'],
//                    [['transporter_type'], function ($attribute, $params) {
//                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'transporter_type');
//                }, 'on' => 'importCsv'],
            [['is_active'], 'default', 'value' => 1],
                [['billing_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'billing_type_code');
                }, 'except' => ['activation']],
                [['vendor_code'], 'unique', 'except' => ['activation']]
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblTransporter', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'transporter_code' => Yii::t('app', 'Transporter Code'),
            'transporter_name' => Yii::t('app', 'Transporter Name'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'address' => Yii::t('app', 'Address'),
            'phone_no' => Yii::t('app', 'Phone No'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'email' => Yii::t('app', 'Email'),
            'pincode' => Yii::t('app', 'Pincode'),
            'registration_no' => Yii::t('app', 'Registration No'),
            'contact_person' => Yii::t('app', 'Contact Person'),
            'local_contact_person' => Yii::t('app', 'Contact Person Hindi Name'),
            'bank_code' => Yii::t('app', 'Bank'),
            'branch_code' => Yii::t('app', 'Branch'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'ifsc' => Yii::t('app', 'IFSC'),
            'gstin' => Yii::t('app', 'GSTIN'),
            'tds_per' => Yii::t('app', 'TDS %'),
            'pan_no' => Yii::t('app', 'PAN No'),
            'beneficiary_name' => Yii::t('app', 'Beneficiary Name'),
            'agreement_no' => Yii::t('app', 'Agreement No'),
            'declaration' => Yii::t('app', 'Declaration'),
            'security_cheque_no' => Yii::t('app', 'Security Cheque No'),
            'union_code' => Yii::t('app', 'Union'),
            'state_code' => Yii::t('app', 'State'),
            'district_code' => Yii::t('app', 'District'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'village_code' => Yii::t('app', 'Village'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'security_amount' => Yii::t('app', 'Security Amount'),
            'billing_type_code' => Yii::t('app', 'Billing Type')
        ];
    }

    /**
     * @inheritdoc
     * @return TblTransporterQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblTransporterQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }

    public function getCode() {
        $data = $this->find()->select(["MAX(CONVERT(INT,substring(transporter_code,4,5))) AS transporter_code"])->where(['union_code' => $this->union_code])->one();
        return $this->union_code . str_pad((int) $data['transporter_code'] + 1, 5, '0', STR_PAD_LEFT);
    }

    public function setChildTable(&$model, &$modelList, &$errors) {
        $existData = $model::find()->where(['transporter_code' => $model->transporter_code])->one();
        $defaultBankDetail = '';
        $defaultContactDetail = '';
        if (!empty($existData)) {
            $defaultBankDetail = $existData->defaultBankDetail;
            $defaultContactDetail = $existData->defaultContactDetail;
        }
        if (empty($defaultBankDetail) || $defaultBankDetail->bank_account_no != $model->bank_account_no) {
            if (!empty($defaultBankDetail)) {
                $defaultBankDetail->is_default = 0;
                $defaultBankDetail->is_active = 0;
                array_push($modelList, $defaultBankDetail);
            }
            $model->setbankDetails($model, $modelList, $errors);
        } elseif (!empty($defaultBankDetail)) {
            $defaultBankDetail->ifsc = $model->ifsc;
            $defaultBankDetail->branch_code = Yii::$app->general->getforeignkey($model->ifscDetail, 'branch_code');
            $defaultBankDetail->bank_code = Yii::$app->general->getforeignkey($model->ifscDetail, 'bank_code');
            if (empty($defaultBankDetail->branch_code)) {
                $this->addError('ifsc', Yii::t('app/validation', $this->getAttributeLabel('ifsc') . ' is Invalid.'));
                return false;
            }
            array_push($modelList, $defaultBankDetail);
        }

        if (empty($defaultContactDetail) || $defaultContactDetail->mobile_no != $model->mobile_no) {
            if (!empty($defaultContactDetail)) {
                $defaultContactDetail->is_default = 0;
                $defaultContactDetail->is_active = 0;
                array_push($modelList, $defaultContactDetail);
            }
            $model->setContactDetails($model, $modelList, $errors);
        } elseif (!empty($defaultContactDetail)) {
            $defaultContactDetail->department = $model->department;
            $defaultContactDetail->contact_person = $model->contact_person;
            $defaultContactDetail->firstname = $model->contact_person;
            $defaultContactDetail->local_contact_person = $model->local_contact_person;
            $defaultContactDetail->lastname = $model->middle_name;
            $defaultContactDetail->surname = $model->surname;
            $defaultContactDetail->local_lastname = $model->local_middlename;
            $defaultContactDetail->local_surname = $model->local_surname;
            array_push($modelList, $defaultContactDetail);
        }
    }

    public function setbankDetails($model, &$saveModel, &$errors) {
        if (!empty($model->bank_account_no)) {
            $branch_model = new TblBankDetails();
            $branch_model->setModel('transporter', $model->transporter_code);
            $branch_model->ifsc = $model->ifsc;
            $branch_model->bank_account_no = $model->bank_account_no;
            $branch_model->branch_code = Yii::$app->general->getforeignkey($this->ifscDetail, 'branch_code');
            $branch_model->bank_code = Yii::$app->general->getforeignkey($this->ifscDetail, 'bank_code');
            if (empty($branch_model->branch_code)) {
                $this->addError('ifsc', Yii::t('app/validation', $this->getAttributeLabel('ifsc') . ' is Invalid.'));
                return false;
            }
            if (!$branch_model->validate()) {
                $errors[] = $branch_model->getErrors();
            }
            array_push($saveModel, $branch_model);
        }
    }

    public function setContactDetails($model, &$saveModel, &$errors) {
        if (!empty($model->mobile_no)) {
            $contact_model = new TblContactDetails();
            $contact_model->setModel('transporter', $model->transporter_code);
            $contact_model->department = $model->department;
            $contact_model->contact_person = $model->contact_person;
            $contact_model->firstname = $model->contact_person;
            $contact_model->local_contact_person = $model->local_contact_person;
            $contact_model->lastname = $model->middle_name;
            $contact_model->surname = $model->surname;
            $contact_model->local_lastname = $model->local_middlename;
            $contact_model->local_surname = $model->local_surname;
            $contact_model->mobile_no = $model->mobile_no;
            $contact_model->email = $model->email;
            if (!$contact_model->validate()) {
                $errors[] = $contact_model->getErrors();
            }
            array_push($saveModel, $contact_model);
        }
    }

    public function validateHamlet($attribute, $params) {
        if (!empty($this->hamlet_code)) {

            $hamlet = Yii::$app->general->validateActiveRelation($this, 'TblHamlets', 'hamlet_code', 'hamlet_code', $this->getAttributeLabel($attribute), 'dcs', 'village_code');
            if ($hamlet['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $hamlet['msg']));
                return false;
            } else {
                $hamlet = $hamlet['model'];
                $vilage = Yii::$app->general->validateActiveRelation($hamlet, 'TblVillages', 'village_code', 'village_code', 'village', 'dcs', 'sub_district_code');
                if ($vilage['msg'] != '') {
                    $this->addError($attribute, Yii::t('app/validation', $vilage['msg']));
                    return false;
                } else {
                    $this->village_code = $hamlet->village_code;
                    $subDistricts = Yii::$app->general->validateActiveRelation($vilage['model'], 'TblSubDistricts', 'sub_district_code', 'sub_district_code', 'sub district', 'dcs', 'district_code');
                    if ($subDistricts['msg'] != '') {
                        $this->addError($attribute, Yii::t('app/validation', $subDistricts['msg']));
                        return false;
                    } else {
                        $this->sub_district_code = $vilage['model']->sub_district_code;
                        $districts = Yii::$app->general->validateActiveRelation($subDistricts['model'], 'TblDistricts', 'district_code', 'district_code', 'district', 'dcs', 'state_code');
                        if ($districts['msg'] != '') {
                            $this->addError($attribute, Yii::t('app/validation', $districts['msg']));
                            return false;
                        } else {
                            $this->district_code = $subDistricts['model']->district_code;
                            if (empty($this->union_code)) {
                                return false;
                            }
                            $mapping = new TblUnionsDistrictMapping();
                            $map = $mapping->getRecord($this->union_code, $this->district_code);
                            if (!$map) {
                                $unions = Yii::$app->general->validateActiveRelation($this, 'TblUnions', 'union_code', 'union_code', 'Union Code', 'Union Name', 'union_code');
                                if ($unions['msg'] != '') {
                                    return false;
                                }

                                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " '" . $this->hamlet_code . "'" . ' is invalid.'));
                                return false;
                            } else {
                                $state = Yii::$app->general->validateActiveRelation($districts['model'], 'TblStates', 'state_code', 'state_code', 'state', 'dcs');
                                if ($state != '') {
                                    $this->addError($attribute, Yii::t('app/validation', $state));
                                    return false;
                                } else {
                                    $this->state_code = $districts['model']->state_code;
                                }
                            }
                        }
                    }
                }
//                $this->transporter_code = $this->getCode();
            }
        }
    }

    public function validateAgreeTo($attribute, $params) {
        if (!empty($this->agreement_from_date) && !empty($this->agreement_to_date) && ($this->agreement_to_date < $this->agreement_from_date )) {
            $this->addError('agreement_to_date', Yii::t('app/validation', $this->getAttributeLabel('agreement_to_date') . ' Must be Greater than ' . $this->getAttributeLabel('agreement_from_date')));
            return FALSE;
        }
    }

    public function getBillingType() {
        return $this->hasOne(TblBillingType::className(), ['billing_type_code' => 'billing_type_code']);
    }

    public function getIfscDetail() {
        return $this->hasOne(TblBranch::className(), ['ifsc' => 'ifsc'])->andwhere(['is_active' => 1]);
    }

    public function getDefaultBankDetail() {
        return $this->hasOne(TblBankDetails::className(), ['module_code' => 'transporter_code'])->where(['tbl_bank_details.module_name' => 'transporter', 'tbl_bank_details.is_default' => 1]);
    }

    public function getDefaultContactDetail() {
        return $this->hasOne(TblContactDetails::className(), ['module_code' => 'transporter_code'])->where(['tbl_contact_details.module_name' => 'transporter', 'tbl_contact_details.is_default' => 1]);
    }

    public function setPanNumber($attribute, $params) {
        $this->pan_no = strtoupper($this->pan_no);
    }

}
