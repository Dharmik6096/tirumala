<?php
namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSubCenter;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\geo\models\TblHamlets;
use app\modules\geo\models\TblVillages;

class SubCenterImport extends TblSubCenter
{
    public $unionCode = '';
    
    public function rules() {
        
        $array = parent::rules();
        
        $rules = [
            [['destination_code'], 'validateDestinationCode'],
            [['hamlet_code'], 'validateHamlet'],
            [['route_code'], 'validateRoute'],
            [['is_bmc'], function ($attribute, $params) {
                    Yii::$app->general->validateIsBmc($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['branch_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBranch($this, $attribute,$params);
                },'skipOnEmpty'=> false],
        ];
        
        foreach ($rules as $row){
            array_push($array, $row);
        }
        return $array;
    }
    
    public function validateDestinationCode($attribute, $params) {
        if(!empty($this->destination_code)){
            $unionCode = $this->getDcsRecord();
            if($this->is_bmc==0 || $this->is_bmc==1 || $this->is_bmc==2){
                $destination = TblMccPlant::find()->select('mcc_plant_code')->where(['union_code'=> $unionCode,'is_active'=>1])->asArray()->all();
                if(!in_array($this->destination_code,array_column($destination, 'mcc_plant_code'),true)){
                  $this->addError($attribute, Yii::t('app/validation','Destination Code is Invalid.'));
                  return false;  
                }else if(!in_array($this->destination_type, ['1', '2'])){
                  $this->addError('destination_type', Yii::t('app/validation',$this->getAttributeLabel('destination_type').' is invalid.'));
                  return false;  
                }
            }else{
                $dcs = new TblDcs();
                $dcs = $dcs->getDcs($unionCode, '');
                if(!in_array($this->destination_code,array_column($dcs, 'dcs_code'),true)){
                  $this->addError($attribute, Yii::t('app/validation','Destination Code "'.$this->destination_code.'" is invalid.'));
                  return false;  
                }
                $this->destination_type='0';
            }
        }
    }
    
    public function validateHamlet($attribute, $params) {
        if(!empty($this->hamlet_code)){
            $hamlet = new TblHamlets();
            $hamlet= $hamlet->getRecord($this->hamlet_code);
            if(!$hamlet){
               $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) ." '".$this->hamlet_code."'". ' is invalid.'));
               return false;
            }else{
               $village = $hamlet->village_code;
               $mapping = new TblDcsVillageMapping();
               $map = $mapping->getRecord($this->dcs_code, $village);
               if(!$map){
                   $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) ." '".$this->hamlet_code."'". ' is invalid.'));
                   return false;
               }else{
                   $this->village_code = $village;
                   $this->sub_district_code = $hamlet->villageCode->sub_district_code;
                   $this->district_code = $hamlet->villageCode->subDistrictCode->district_code;
                   $this->state_code = $hamlet->villageCode->subDistrictCode->districtCode->state_code;
               }
            }
        }
    }
    
    public function validateRoute($attribute, $params) {
        if(!empty($this->route_code)){
            $unionCode = $this->getDcsRecord();
            $route = new TblRoutes();
            $route = $route->getRoutes($unionCode);
            if (!in_array($this->route_code,array_keys($route),true)) {
               $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) ." '".$this->route_code."'". ' is invalid.'));
               return false;
            }
        }
    }
    
    public function getDcsRecord(){
        
        $dcs = new TblDcs();
        $dcs = $dcs->getDcsName($this->dcs_code);
        if(!$dcs){
            $this->addError('dcs_code', Yii::t('app/validation', "Dcs Code '".$this->dcs_code."'". ' is invalid.'));
            return false;
        }else
           return $dcs->union_code;
    }
}