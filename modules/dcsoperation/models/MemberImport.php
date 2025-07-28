<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\geo\models\TblHamlets;
use app\modules\dcsoperation\models\TblMemberTypes;
use app\modules\general\models\TblBloodgroup;
use app\modules\general\models\TblGender;
use app\modules\general\models\TblQualification;
use app\modules\globalmaster\models\TblCasteCategory;
use app\modules\general\models\TblReligion;
use app\modules\organisation\models\TblUnionsDistrictMapping;
use app\modules\general\models\TblRelationship;
use app\modules\organisation\models\TblBanks;
use app\modules\organisation\models\TblBranch;
use app\modules\organisation\models\TblBanksDistrictsMapping;

class MemberImport extends TblMember {

    public $import_union_code, $import_eipl_code;

    public function rules() {
        $main_rules = [
                [['is_download'], 'default', 'value' => '0'],
                [['is_verified'], 'default', 'value' => '0'],
                [['is_contact_verified'], 'default', 'value' => '0'],
                [['is_active', 'member_type_code'], 'default', 'value' => '1'],
                [['dcs_code', 'member_name'], 'required'],
                [['dcs_code'], 'validateDcs', 'skipOnEmpty' => TRUE, 'on' => 'importCsv'],
                [['member_code', 'dcs_code', 'member_name', 'father_name', 'surname', 'nominee_name', 'dob', 'land_class', 'total_land', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'address', 'pan_no', 'adhar_no', 'village_code', 'created_by', 'updated_by', 'hamlet_code', 'sub_district_code', 'district_code', 'state_code', 'union_code', 'local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address', 'payment_mode', 'voter_id', 'vendor_code', 'sap_farmer_code'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['member_code', 'federation_code', 'dcs_code', 'ex_member_code', 'member_name', 'father_name', 'surname', 'nominee_name', 'dob', 'bloodgroup_code', 'gender_code', 'qualification_code', 'caste_category_code', 'land_class', 'total_land', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals', 'member_type_code', 'bank_code', 'branch_code', 'bank_account_no', 'ifsc', 'mobile_no', 'email', 'address', 'pincode', 'pan_no', 'adhar_no', 'annual_income', 'village_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_active', 'payment_mode', 'animal_type_code', 'hamlet_code', 'sub_district_code', 'district_code', 'state_code', 'union_code', 'bank_name', 'branch_name', 'local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address', 'nominee_relation', 'voter_id', 'religion_code', 'upload', 'download_date_time', 'is_download', 'member_class', 'registration_date', 'ref_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'mobile_no', 'ifsc'], 'safe'],
                [['qualification_code', 'caste_category_code', 'no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind', 'total_animals', 'member_type_code', 'annual_income', 'is_active', 'animal_type_code', 'bloodgroup_code', 'gender_code', 'nominee_relation', 'religion_code'], 'integer', 'min' => 0, 'message' => Yii::t('app/validation', '{attribute} must be a digit.e.g."10"')],
                [['created_at', 'updated_at', 'federation_code', 'bank_name', 'branch_name', 'upload', 'religion_code', 'is_download', 'download_date_time', 'member_class', 'registration_date', 'ref_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'ex_member_code', 'ref_code', 'beneficiary_name'], 'safe'],
                [['ifsc', 'pan_no'], 'trim'],
                [['email'], 'email'],
                [['member_name', 'father_name', 'surname', 'nominee_name'], function ($attribute, $params) {
                    Yii::$app->general->validateDiscriptiveField($this, $attribute);
                }, 'skipOnEmpty' => TRUE],
                [['mobile_no'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
//                [['mobile_no'], 'unique', 'targetAttribute' => ['mobile_no', 'is_active'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
//                    return $this->is_active;
//                }, 'skipOnEmpty' => true],
            [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
                [['local_name', 'local_father_name', 'local_surname', 'local_nominee_name', 'local_address'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
                [['ifsc'], function ($attribute, $params) {
                    Yii::$app->general->validateIfsc($this, $attribute, $params);
                }, 'skipOnEmpty' => true],
                [['beneficiary_name'], function ($attribute, $params) {
                    Yii::$app->general->validateBeneficiary($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['androidsync']],
                [['voter_id'], 'string', 'max' => 15, 'skipOnEmpty' => true],
                [['payment_mode'], 'string', 'max' => 10, 'skipOnEmpty' => true],
                [['hamlet_code'], 'validateHamlet', 'skipOnEmpty' => TRUE],
                [['no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind'], 'validateNoOfAnimal'],
//            [['branch_code'], function ($attribute, $params) {
//                    Yii::$app->general->validateBranch($this, $attribute, $params);
//                }, 'skipOnEmpty' => TRUE],
            [['dob', 'registration_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2017-11-01'), 'skipOnEmpty' => true],
                [['member_type_code', 'member_class'], 'in', 'range' => [1, 2], 'skipOnEmpty' => TRUE],
                [['gender_code'], 'in', 'range' => [1, 2, 3], 'skipOnEmpty' => TRUE],
                [['bloodgroup_code'], 'in', 'range' => [1, 2, 3, 4, 5], 'skipOnEmpty' => TRUE],
                [['qualification_code'], 'in', 'range' => [1, 2, 3, 4, 5, 6, 7], 'skipOnEmpty' => TRUE],
                [['caste_category_code'], 'in', 'range' => [1], 'skipOnEmpty' => TRUE],
                [['religion_code'], 'in', 'range' => [1, 2, 3, 4, 5, 6], 'skipOnEmpty' => TRUE],
                [['animal_type_code'], 'in', 'range' => [1, 2, 3], 'skipOnEmpty' => TRUE],
                [['nominee_relation'], 'in', 'range' => [1, 2, 3, 4, 5, 6, 7, 8], 'skipOnEmpty' => TRUE],
                [['bank_account_no'], function ($attribute, $params) {
                    $error = TblBanks::validateAccountNo($this->bank_code, $this->$attribute);
                    if ($error !== TRUE)
                        $this->addError($attribute, $error);
                }, 'skipOnEmpty' => TRUE],
//            ['bank_account_no', 'unique', 'targetAttribute' => ['bank_account_no', 'ifsc', 'is_active', 'dcs_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
//            return $this->is_active;
//        }, 'skipOnEmpty' => TRUE],
//            [['x_col3'], 'default', 'value' => 15],
            [['pan_no'], 'unique', 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'on' => ['importCsv']],
                [['adhar_no'], 'unique', 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'on' => ['importCsv']],
                [['dcs_code'], 'setXcol3', 'on' => ['importCsv']],
                [['dcs_code'], 'setBankDetail', 'on' => ['importCsv']],
                [['rate_class'], 'default', 'value' => '0'],
                [['dcs_code'], 'setVerified', 'on' => ['importCsv']],
                [['pan_no'], 'setPanNumber', 'on' => ['importCsv']],
                [['rate_class'], function ($attribute, $params) {
                    !empty($this->rate_class) ? Yii::$app->general->validateGlobalStatic($this, $attribute, 'rate_class') : '';
                }, 'skipOnEmpty' => TRUE, 'on' => 'importCsv'],
                [['vendor_code'], 'unique', 'targetAttribute' => ['vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
                    return $this->is_active;
                }, 'skipOnEmpty' => true, 'on' => ['importCsv']],
                [['vendor_code'], 'number', 'on' => ['importCsv']],
                [['vendor_code'], 'string', 'max' => 10, 'skipOnEmpty' => true],
            /*  [['dob'], function ($attribute, $params) {
              Yii::$app->general->validateAge($this, $attribute, $params);
              }, 'skipOnEmpty' => true, 'except' => ['saveCreamyData', 'androidsync']],

              [['pan_no'], 'unique', 'targetAttribute' => ['pan_no', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
              return $this->is_active;
              }],
              [['email'], 'unique', 'targetAttribute' => ['email', 'is_active', 'dcs_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
              return $this->is_active;
              }],

              ['ref_code', 'unique', 'targetAttribute' => ['ref_code', 'union_code'], 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
              [['member_type_code'], 'validateMemberType'],
              [['gender_code'], 'validateGender'],
              [['bloodgroup_code'], 'validateBloodgroup'],
              [['qualification_code'], 'validateQualification'],
              [['caste_category_code'], 'validateCasteCategory'],
              [['religion_code'], 'validateReligion'],
              [['animal_type_code'], 'validateAnimalType'],
              [['nominee_relation'], 'validateRelationship'],
              [['member_class'], 'validateClass'],
             */
                [['member_code'], 'setNullValue'],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblMember', $this->form_validation_type, $this->import_eipl_code);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    public function validateCasteCategory($attribute, $params) {
        if (!empty($this->caste_category_code)) {
            $caste_category = Yii::$app->general->validateActiveRelation($this, 'TblCasteCategory', 'caste_category_code', 'caste_category_code', $this->getAttributeLabel($attribute), 'Caste Category Code', 'caste_category_code');
            if ($caste_category['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $caste_category['msg']));
                return false;
            }
        }
    }

    public function validateDcs($attribute, $params) {
        $dcs = new TblDcs();
        $this->dcs_code = $dcs->getValidDcs($this->dcs_code);
        if (empty($this->dcs_code)) {
            $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
            return false;
        } else {
            $dcs_data = $this->dcsCode;
            $this->union_code = $dcs_data->union_code;
            if ($this->union_code != $this->import_union_code) {
                $this->addError($attribute, Yii::t('app/validation', 'Society Code is invalid for Mapped Union.'));
                return false;
            }
            $this->state_code = $dcs_data->state_code;
            $this->district_code = $dcs_data->district_code;
            $this->sub_district_code = $dcs_data->sub_district_code;
            $this->village_code = $dcs_data->village_code;
            $this->federation_code = Yii::$app->general->getforeignkey($this->unionCode, 'federation_code');
            if (empty($this->no_of_buffalo) && empty($this->no_of_cow_cross) && empty($this->no_of_cow_ind))
                $this->total_animals = $this->no_of_buffalo = $this->no_of_cow_cross = $this->no_of_cow_ind = 0;
        }
    }

    public function validateGender($attribute, $params) {
        if (!empty($this->gender_code)) {
            $gender = new TblGender();
            if ($gender->getGender($this->gender_code) <= 0) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " Code '" . $this->gender_code . "'" . ' is invalid.'));
                return false;
            }
        }
    }

    public function validateBloodgroup($attribute, $params) {
        if (!empty($this->bloodgroup_code)) {
            $bloodgroup = new TblBloodgroup();
            if ($bloodgroup->getBloodGroup($this->bloodgroup_code) <= 0) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " Code '" . $this->bloodgroup_code . "'" . ' is invalid.'));
                return false;
            }
        }
    }

    public function validateQualification($attribute, $params) {
        if (!empty($this->qualification_code)) {
            $qual = new TblQualification();
            if ($qual->getQualification($this->qualification_code) <= 0) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " Code '" . $this->qualification_code . "'" . ' is invalid.'));
                return false;
            }
        }
    }

    public function validateReligion($attribute, $params) {
        if (!empty($this->religion_code)) {
            $religion = new TblReligion();
            if ($religion->getReligion($this->religion_code) <= 0) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " Code '" . $this->religion_code . "'" . ' is invalid.'));
                return false;
            }
        }
    }

    public function validateMemberType($attribute, $params) {
        if (!empty($this->member_type_code)) {
            $type = new TblMemberTypes();
            $type = $type->getMemberType();
            if (!in_array($this->member_type_code, array_keys($type))) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " Code '" . $this->member_type_code . "'" . ' is invalid.'));
                return false;
            }
        }
    }

    public function validateHamlet($attribute, $params) {
        if (!empty($this->hamlet_code)) {
            $hamlet = Yii::$app->general->validateActiveRelation($this, 'TblHamlets', ['hamlet_code', 'village_code'], ['hamlet_code', 'village_code'], $this->getAttributeLabel($attribute), 'dcs', 'hamlet_code');
            if ($hamlet['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $hamlet['msg']));
                return false;
            }
        }
    }

    public function validateAnimalType($attribute, $params) {
        if (!empty($this->animal_type_code)) {

            $animalType = Yii::$app->general->validateActiveRelation($this, 'TblAnimalType', 'animal_type_code', 'animal_type_code', 'Milk Type Code', 'Milk Type', 'animal_type_code');
            if ($animalType['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $animalType['msg']));
                return false;
            }
        }
    }

    public function validateNoOfAnimal($attribute, $params) {
        if (isset($this->no_of_buffalo) || isset($this->no_of_cow_cross) || isset($this->no_of_cow_ind)) {
            isset($this->no_of_buffalo) ? $this->no_of_buffalo = (int) $this->no_of_buffalo : $this->no_of_buffalo = (int) 0;
            isset($this->no_of_cow_cross) ? $this->no_of_cow_cross = (int) $this->no_of_cow_cross : $this->no_of_cow_cross = (int) 0;
            isset($this->no_of_cow_ind) ? $this->no_of_cow_ind = (int) $this->no_of_cow_ind : $this->no_of_cow_ind = (int) 0;
            $this->total_animals = $this->no_of_buffalo + $this->no_of_cow_cross + $this->no_of_cow_ind;
        }
    }

    public function validateRelationship($attribute, $params) {
        if (!empty($this->nominee_relation)) {
            $relationship = new TblRelationship();
            if ($relationship->getRelationship($this->nominee_relation) <= 0) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " Code '" . $this->nominee_relation . "'" . ' is invalid.'));
                return false;
            }
        }
    }

    public function validateClass($attribute, $params) {
        if (!empty($this->member_class)) {
            if (!in_array($this->member_class, [1, 2])) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " Code '" . $this->member_class . "'" . ' is invalid.'));
                return false;
            }
        }
    }

    public function setBankDetail($attribute, $params) {
        if (empty($this->getErrors()) && !empty($this->ifsc)) {
            $this->branch_code = Yii::$app->general->getforeignkey($this->ifscDetail, 'branch_code');
            $this->bank_code = Yii::$app->general->getforeignkey($this->ifscDetail, 'bank_code');
            if (empty($this->branch_code)) {
                $this->addError('ifsc', Yii::t('app/validation', $this->getAttributeLabel('ifsc') . ' is Invalid.'));
                return false;
            } else {
                $district = $this->district_code;
                $mapping = new TblBanksDistrictsMapping();
                $mapping = $mapping->getRecord($this->bank_code, $district);
                if (!$mapping && $this->bankCode->nationalized_bank == 0) {
                    $this->addError('ifsc', Yii::t('app/validation', "District is not mapped in relevant bank for IFSC '" . $this->ifsc . "'."));
                    return false;
                }
            }
        }
    }

    public function getIfscDetail() {
        return $this->hasOne(TblBranch::className(), ['ifsc' => 'ifsc'])->andwhere(['is_active' => 1]);
    }

    public function setVerified($attribute, $params) {
        $existData = $this::find()->where(['union_code' => $this->union_code, 'dcs_code' => $this->dcs_code, 'ex_member_code' => $this->ex_member_code])->one();
        if (!empty($existData)) {
            if ($existData->bank_account_no != $this->bank_account_no || $existData->ifsc != $this->ifsc || $existData->beneficiary_name != $this->beneficiary_name || $existData->pan_no != $this->pan_no || $existData->adhar_no != $this->adhar_no || $existData->voter_id != $this->voter_id) {
                $this->is_verified = 0;
            }
            if ($existData->hamlet_code != $this->hamlet_code || $existData->address != $this->address || $existData->local_address != $this->local_address || $existData->pincode != $this->pincode || $existData->mobile_no != $this->mobile_no || $existData->email != $this->email) {
                $this->is_contact_verified = 0;
            }
        } else {
            $this->is_verified = 0;
            $this->is_contact_verified = 0;
        }
    }

    public function setPanNumber($attribute, $params) {
        $this->pan_no = strtoupper($this->pan_no);
    }

}
