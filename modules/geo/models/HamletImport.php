<?php
namespace app\modules\geo\models;

use Yii;
use app\modules\geo\models\TblHamlets;

class HamletImport extends TblHamlets
{
    public function rules() {
        
        $array = parent::rules();
        
        $rules = [
            [['village_code'], 'validateHamlet'],
        ];
        
        foreach ($rules as $row){
            array_push($array, $row);
        }
        return $array;
    }
    
    public function validateHamlet($attribute, $params) {
        
        if(!empty($this->village_code)){
            if($this->villageCode->is_active!=1){
               $this->addError($attribute, Yii::t('app/validation', "Village Code '".$this->village_code."'". ' is In Active.'));
               return false;
            }
            //$code = 
            $this->hamlet_code = $this->getMaxVillageCode($this->village_code);
        }
    }
}