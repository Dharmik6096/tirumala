<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblMccPlant;
use app\modules\geo\models\TblHamlets;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblStates;
use app\modules\organisation\models\TblPlant;

class MccPlantImport extends TblMccPlant {

    public function rules() {

        $array = parent::rules();

        $rules = [
            [['union_code'], 'validateUnionCode'],
            [['hamlet_code'], 'validateHamlet'],
            [['plant_code'], 'validatePlantCode'],
            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
            [['sub_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubDistricts::className(), 'targetAttribute' => ['sub_district_code' => 'sub_district_code']],
            [['district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDistricts::className(), 'targetAttribute' => ['district_code' => 'district_code']],
            [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStates::className(), 'targetAttribute' => ['state_code' => 'state_code']],
        ];

        foreach ($rules as $row) {
            array_push($array, $row);
        }
        return $array;
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
            $unions = Yii::$app->general->validateActiveRelation($this, 'TblUnions', 'union_code', 'union_code', 'Union Code', 'Union Name', 'union_code');
            if ($unions['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " '" . $this->union_code . "'" . ' is invalid.'));
                return false;
            }
        }
        $this->mcc_plant_code = $this->getCode();
        $this->valid_from = date('Y-m-d');
        $this->is_plant = 0;
    }

    public function validatePlantCode($attribute, $params) {
        if (!empty($this->plant_code)) {
            $plant = Yii::$app->general->validateActiveRelation($this, 'TblPlant', 'plant_code', 'plant_code', 'Plant Code', 'Plant Code', 'plant_code');
            if ($plant['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $plant['msg']));
                return false;
            }
        }
    }

}
