<?php
namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use app\modules\geo\models\TblVillages;

class BranchImport extends TblBranch
{
    public function rules() {
        
        $array = parent::rules();
        
        $rules = [
            [['hamlet_code'], 'validateHamlet'],
        ];
        
        foreach ($rules as $row){
            array_push($array, $row);
        }
        return $array;
    }
    
    public function validateHamlet($attribute, $params) {
        if(!empty($this->hamlet_code)){
            
            $hamlet = Yii::$app->general->validateActiveRelation($this,'TblHamlets','hamlet_code','hamlet_code',$this->getAttributeLabel($attribute),'dcs','village_code');
            if($hamlet['msg']!=''){
                $this->addError($attribute, Yii::t('app/validation', $hamlet['msg']));
                return false;
            }else{
               $hamlet = $hamlet['model'];
               $vilage = Yii::$app->general->validateActiveRelation($hamlet,'TblVillages','village_code','village_code','village','dcs','sub_district_code');
               
               if($vilage['msg']!=''){
                    $this->addError($attribute, Yii::t('app/validation', $vilage['msg']));
                    return false;
               }else{
                    $this->village_code = $hamlet->village_code;
                    $subDistricts = Yii::$app->general->validateActiveRelation($vilage['model'],'TblSubDistricts','sub_district_code','sub_district_code','sub district','dcs','district_code');
                    if($subDistricts['msg']!=''){
                        $this->addError($attribute, Yii::t('app/validation', $subDistricts['msg']));
                        return false;
                    }else{
                        $this->sub_district_code = $vilage['model']->sub_district_code;
                        $districts = Yii::$app->general->validateActiveRelation($subDistricts['model'],'TblDistricts','district_code','district_code','district','dcs','state_code');
                        if($districts['msg']!=''){
                            $this->addError($attribute, Yii::t('app/validation', $districts['msg']));
                            return false;
                        }else{
                            $this->district_code = $subDistricts['model']->district_code;
                            $mapping = new TblBanksDistrictsMapping();
                            $map = $mapping->getRecord($this->bank_code, $this->district_code);
                            if(!$map){
                                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) ." '".$this->hamlet_code."'". ' is invalid.'));
                                return false;
                            } else{
                                $state = Yii::$app->general->validateActiveRelation($districts['model'],'TblStates','state_code','state_code','state','dcs');
                                if($state!=''){
                                    $this->addError($attribute, Yii::t('app/validation',$state));
                                    return false;
                                }else{
                                   $this->state_code= $districts['model']->state_code;
                                   $this->branch_code = $this->getCode();
                                   $this->valid_from =  date('Y-m-d');
                                }
                            }
                        }
                    }
               }
            }
        } else {
            $this->addError($attribute, Yii::t('app/validation', 'Hamlet Code can not be blank'));
        }
    }
    
}