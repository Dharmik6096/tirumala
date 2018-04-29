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

class MemberImport extends TblMember {

    public function rules() {

        $array = parent::rules();

        $rules = [
            [['dcs_code'], function ($attribute, $params) {
            
        }, 'on' => ['customImport']],
            [['dcs_code'], 'validateDcs'],
            [['hamlet_code'], 'validateHamlet'],
            [['member_type_code'], 'validateMemberType'],
            [['gender_code'], 'validateGender'],
            [['bloodgroup_code'], 'validateBloodgroup'],
            [['qualification_code'], 'validateQualification'],
            [['caste_category_code'], 'validateCasteCategory'],
            [['religion_code'], 'validateReligion'],
            [['no_of_buffalo', 'no_of_cow_cross', 'no_of_cow_ind'], 'validateNoOfAnimal'],
            [['branch_code'], function ($attribute, $params) {
            Yii::$app->general->validateBranch($this, $attribute, $params);
        }, 'skipOnEmpty' => false],
            [['dob'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2017-11-01'), 'skipOnEmpty' => true],
            [['animal_type_code'], 'validateAnimalType'],
            [['registration_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2017-11-01'), 'skipOnEmpty' => true],
            [['nominee_relation'], 'validateRelationship'],
            [['member_class'], 'validateClass'],
        ];

        foreach ($rules as $row) {
            array_push($array, $row);
        }
        return $array;
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
        if (!empty($this->dcs_code)) {
            $dcs = Yii::$app->general->validateActiveRelation($this, 'TblDcs', 'dcs_code', 'dcs_code', 'Society Code', 'Society Code', 'dcs_code');
            if ($dcs['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $dcs['msg']));
                return false;
            } else {
                if (!empty($this->oldattributes['dcs_code'])) {
                    $this->dcs_code = $this->oldattributes['dcs_code'];
                }
                $this->union_code = $this->dcsCode->union_code;
                $unions = explode(',', Yii::$app->session->get('Unions'));
                if (!(in_array($this->union_code, $unions))) {
                    $this->addError($attribute, Yii::t('app/validation', 'Society Code is invalid for Mapped Union.'));
                    return false;
                }
                $this->state_code = $this->dcsCode->state_code;
                $this->district_code = $this->dcsCode->district_code;
                $this->sub_district_code = $this->dcsCode->sub_district_code;
                $this->village_code = $this->dcsCode->village_code;
                $this->federation_code = $this->dcsCode->unionCode->federation_code;
                if (empty($this->no_of_buffalo) && empty($this->no_of_cow_cross) && empty($this->no_of_cow_ind))
                    $this->total_animals = $this->no_of_buffalo = $this->no_of_cow_cross = $this->no_of_cow_ind = 0;
            }
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

}
