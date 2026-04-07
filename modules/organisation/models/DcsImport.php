<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\geo\models\TblHamlets;
use app\modules\globalmaster\models\TblDcsTypes;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblStates;
use app\modules\organisation\models\TblBranch;

class DcsImport extends TblDcs {

    public function rules() {

        $array = parent::rules();

        $rules = [
            /*   [['bmc_code'], function ($attribute, $params) {
              Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
              }, 'on' => ['importCsv']], */
            [['union_code', 'bmc_code', 'dcs_code', 'dcs_code_ex', 'dcs_name', 'dcs_short_name'], 'required', 'on' => ['customImport']],
            [['dpu_type'], 'required', 'on' => 'importCsv'],
            [['union_code'], 'validateUnionCode', 'when' => function ($model) {
                    return $model->isAttributeChanged('union_code', FALSE);
                }],
            [['hamlet_code'], 'validateHamlet', 'when' => function ($model) {
                    return $model->isAttributeChanged('hamlet_code', FALSE);
                }],
            [['dcs_type_code'], 'validateDcsType', 'when' => function ($model) {
                    return $model->isAttributeChanged('dcs_type_code', FALSE);
                }],
            [['registration_date', 'effective_date', 'security_return_date'], 'convertDateDot'],
            [['registration_date', 'effective_date', 'security_return_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018')],
            [['registration_date', 'effective_date', 'security_return_date'], 'convertDate'],
            //  [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
            //  [['sub_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubDistricts::className(), 'targetAttribute' => ['sub_district_code' => 'sub_district_code']],
            //  [['district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDistricts::className(), 'targetAttribute' => ['district_code' => 'district_code']],
            //  [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStates::className(), 'targetAttribute' => ['state_code' => 'state_code']],
            [['dpu_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'dpu_type');
                }, 'on' => 'importCsv'],
            [['security_return_mode'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'security_return_mode');
                }, 'on' => 'importCsv'],
            ['auto_member_create', 'in', 'range' => [0, 1], 'on' => ['importCsv'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Create Auto Member either 1 or 0')],
            [['cheque_amount', 'security_return_amt'], 'number', 'on' => ['importCsv']],
        ];

        foreach ($rules as $row) {
            array_push($array, $row);
        }
        return $array;
    }

    public function validateDcsType($attribute, $params) {
        if (!empty($this->dcs_type_code)) {
            $type = new TblDcsTypes();
            $type = $type->getActiveDcsTypes();
            if (!in_array($this->dcs_type_code, array_keys($type))) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " '" . $this->dcs_type_code . "'" . ' is invalid.'));
                return false;
            }
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
                //$this->dcs_code = $this->getCode();
//                $this->dcs_code = (strlen($this->dcs_code) <= 10 && (in_array($this->union_code, ['001', '002']))) ? '00' . $this->dcs_code : $this->dcs_code;
            }
        }
    }

    public function validateUnionCode($attribute, $params) {
        if (!empty($this->union_code)) {
            $unions = explode(',', Yii::$app->session->get('Unions'));
            if (!(in_array($this->union_code, $unions))) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " '" . $this->union_code . "'" . ' is invalid.'));
                return false;
            }
        }
        $unions = Yii::$app->general->validateActiveRelation($this, 'TblUnions', 'union_code', 'union_code', 'Union Code', 'Union Name', 'union_code');
        if ($unions['msg'] != '') {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " '" . $this->union_code . "'" . ' is invalid.'));
            return false;
        }
    }

    public function convertDateDot() {
        try {
            $this->registration_date = Yii::$app->controls->view_date($this->registration_date, 'php:d.m.Y');
        } catch (\Throwable $e) {
            $this->registration_date = '-';
        }
        try {
            $this->effective_date = Yii::$app->controls->view_date($this->effective_date, 'php:d.m.Y');
        } catch (\Throwable $e) {
            $this->effective_date = '-';
        }
        try {
            $this->security_return_date = Yii::$app->controls->view_date($this->security_return_date, 'php:d.m.Y');
        } catch (\Throwable $e) {
            $this->security_return_date = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->registration_date = !empty($this->registration_date) ? Yii::$app->controls->view_date($this->registration_date, 'php:Y-m-d') : NULL;
            $this->effective_date = !empty($this->effective_date) ? Yii::$app->controls->view_date($this->effective_date, 'php:Y-m-d') : NULL;
            $this->security_return_date = !empty($this->security_return_date) ? Yii::$app->controls->view_date($this->security_return_date, 'php:Y-m-d') : NULL;
        }
    }

}
