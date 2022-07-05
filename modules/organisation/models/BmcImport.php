<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\geo\models\TblHamlets;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblStates;
use app\modules\general\models\TblBmcType;
use app\modules\globalmaster\models\TblCapacity;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblMccPlant;

class BmcImport extends TblDcsBmc {

    public function rules() {

        $array = parent::rules();

        $rules = [
            [['union_code'], 'validateUnionCode'],
            [['mcc_plant_code'], 'validateMccCode'],
            [['manufacturer_code'], 'validateManufacturerCode'],
            [['bmc_type_code'], 'validateBmcTypeCode'],
            [['capacity'], 'validateCapacity'],
            [['bmc_milk_type'], 'validateBmcMilkType'],
            [['hamlet_code'], 'validateHamlet'],
            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
            [['sub_district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubDistricts::className(), 'targetAttribute' => ['sub_district_code' => 'sub_district_code']],
            [['district_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDistricts::className(), 'targetAttribute' => ['district_code' => 'district_code']],
            [['state_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStates::className(), 'targetAttribute' => ['state_code' => 'state_code']],
            [['channel_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'channel');
                }, 'on' => 'importCsv'],
            [['channel_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblChannelMaster::className(), 'targetAttribute' => ['channel_type' => 'channel_master_code'], 'on' => ['importCsv']],
            [['channel_type'], 'setChannel'],
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

    public function validateManufacturerCode($attribute, $params) {
        if (!empty($this->manufacturer_code)) {
            $manufacturer = Yii::$app->general->validateActiveRelation($this, 'TblManufacturer', 'id', 'manufacturer_code', 'Manufacturer Code', 'Manufacturer Name', 'id');
            if ($manufacturer['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $manufacturer['msg']));
                return false;
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
        $this->bmc_code = $this->getCode();
        $this->valid_from = date('Y-m-d');
        $this->is_mcc = 0;
    }

    public function validateMccCode($attribute, $params) {
        if (!empty($this->mcc_plant_code)) {
            $mcc = Yii::$app->general->validateActiveRelation($this, 'TblMccPlant', 'mcc_plant_code', 'mcc_plant_code', 'Mcc Code', 'Mcc Code', 'mcc_plant_code');
            if ($mcc['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $mcc['msg']));
                return false;
            }
        }
    }

    public function validateBmcTypeCode($attribute, $params) {
        if (!empty($this->bmc_type_code)) {
            $bmcType = Yii::$app->general->validateActiveRelation($this, 'TblBmcType', 'bmc_type_code', 'bmc_type_code', 'Bmc Type Code', 'Bmc Type Code', 'bmc_type_code');
            if ($bmcType['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $bmcType['msg']));
                return false;
            }
        }
    }

    public function validateCapacity($attribute, $params) {
        if (!empty($this->capacity)) {
            $capacity = Yii::$app->general->validateActiveRelation($this, 'TblCapacity', 'capacity_code', 'capacity', 'Capacity Code', 'capacity', 'capacity_code');
            if ($capacity['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $capacity['msg']));
                return false;
            }
        }
    }

    public function validateBmcMilkType($attribute, $params) {
        if (!empty($this->bmc_milk_type)) {
            $bmcMilkType = Yii::$app->general->validateActiveRelation($this, 'TblAnimalType', 'animal_type_code', 'bmc_milk_type', $this->getAttributeLabel($attribute), 'Bmc Milk Type', 'animal_type_code');
            if ($bmcMilkType['msg'] != '') {
                $this->addError($attribute, Yii::t('app/validation', $bmcMilkType['msg']));
                return false;
            }
        }
    }

    public function setChannel($attribute, $params) {
        if (!empty($this->channel_type)) {
            $this->x_col1 = $this->channel_type;
        }
    }

}
