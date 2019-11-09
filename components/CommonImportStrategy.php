<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\components;

use ruskid\csvimporter\ARImportStrategy;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\PlantImport;
use app\modules\organisation\models\TblMccPlant;

use yii\widgets\ActiveForm;

class CommonImportStrategy extends ARImportStrategy{
    
    public function import(&$data) {
        $importedPks = [];
        $errors=[];
        $count=0;
        
                      
            //$abc = array_map('array_filter', $data);
            $data = array_filter($data, function($var){  return !empty($var[0]) && !is_null($var);} );
            $data = array_filter($data);
            
            foreach ($data as $key=>$row) {              
                
                $skipImport = isset($this->skipImport) ? call_user_func($this->skipImport, $row) : false;

                if($key==0)
                    continue;

                if (!$skipImport) {
                    $trans = \Yii::$app->db->beginTransaction();
                    try {
                    /* @var $model \yii\db\ActiveRecord */
                    $class = $this->className;
                    $model = new $this->className;
                    if(!empty($this->scenario))
                        $model->scenario = $this->scenario;
                       
                    
                    $uniqueAttributes = [];
                    foreach ($this->configs as $config) {
                        if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                            $value = call_user_func($config['value'], $row);

                            //Create array of unique attributes
                            if (isset($config['unique']) && $config['unique']) {
                                $uniqueAttributes[$config['attribute']] = $value;
                            }

                            //Set value to the model
                            $model->setAttribute($config['attribute'], $value);
                        }
                    }
                    if(isset($this->defaultFields)){
                        foreach ($this->defaultFields as $default) {
    //                        var_dump($default['value']);exit;
                            if (isset($default['attribute']) && $model->hasAttribute($default['attribute'])) {
                                $value = $default['value'];
                                //Set value to the model
                                $model->setAttribute($default['attribute'], $value);
                            }
                        }
                    }
                  
                    $modelList = [];
                    $error = ActiveForm::validate($model);
                    
                    if ($model->hasAttribute('is_active')) {
                        $nm = ucwords(str_replace('_', ' ', 'is_active'));
                        if(!(preg_match('/^[0-9]*$/', $model->is_active))){
                            $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
                        }
                        if($model->is_active != 0 && $model->is_active != 1){
                            $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
                        }
                    }
                                        
                    if(empty($model->getErrors()) && $model->validate()){
                        array_push($modelList, $model);
//                        if($class == 'app\modules\organisation\models\PlantImport') {
//                            $mccModel=new TblMccPlant();
//                            $mccModel->plant_code=$model->plant_code;
//                            $mccModel->mcc_plant_code=$mccModel->getCode();
//                            $mccModel->name= $model->name;
//                            $mccModel->local_name= $model->local_name;
//                            $mccModel->capacity= $model->capacity;
//                            $mccModel->is_active= $model->is_active;
//                            $mccModel->state_code= $model->state_code;
//                            $mccModel->district_code= $model->district_code;
//                            $mccModel->sub_district_code= $model->sub_district_code;
//                            $mccModel->village_code= $model->village_code;
//                            $mccModel->hamlet_code= $model->hamlet_code;
//                            $mccModel->union_code= $model->union_code;
//                            $mccModel->valid_from= $model->valid_from;
//                            $mccModel->is_plant=1; 
//                            array_push($modelList, $mccModel);
//
//                            $bmcModel=new TblDcsBmc();
//                            $bmcModel->scenario='from_mcc';
//                            $bmcModel->mcc_plant_code=$mccModel->mcc_plant_code;
//                            $bmcModel->bmc_code=$bmcModel->getCode();
//                            $bmcModel->bmc_name=  $mccModel->name;
//                            $bmcModel->local_name=  $mccModel->local_name;
//                            $bmcModel->capacity=  $mccModel->capacity;
//                            $bmcModel->is_active= $mccModel->is_active;
//                            $bmcModel->state_code= $mccModel->state_code;
//                            $bmcModel->district_code= $mccModel->district_code;
//                            $bmcModel->sub_district_code= $mccModel->sub_district_code;
//                            $bmcModel->village_code= $mccModel->village_code;
//                            $bmcModel->hamlet_code= $mccModel->hamlet_code;
//                            $bmcModel->union_code= $mccModel->union_code;
//                            $bmcModel->valid_from= $model->valid_from;
//                            $bmcModel->is_mcc=1;
//                            array_push($modelList, $bmcModel);
//
//                        }
//                        if($class == 'app\modules\organisation\models\MccPlantImport') { 
//                            $bmcModel=new TblDcsBmc();
//                            $bmcModel->scenario='from_mcc';
//                            $bmcModel->mcc_plant_code=$model->mcc_plant_code;
//                            $bmcModel->bmc_code=$model->mcc_plant_code;
//                            $bmcModel->bmc_code=$bmcModel->getCode();
//                            $bmcModel->bmc_name=  $model->name;
//                            $bmcModel->local_name=  $model->local_name;
//                            $bmcModel->capacity=  $model->capacity;
//                            $bmcModel->is_active= $model->is_active;
//                            $bmcModel->state_code= $model->state_code;
//                            $bmcModel->district_code= $model->district_code;
//                            $bmcModel->sub_district_code= $model->sub_district_code;
//                            $bmcModel->village_code= $model->village_code;
//                            $bmcModel->hamlet_code= $model->hamlet_code;
//                            $bmcModel->union_code= $model->union_code;
//                            $bmcModel->valid_from= $model->valid_from;
//                            $bmcModel->is_mcc=1;
//                            array_push($modelList, $bmcModel);
//                        }
                        
//                        var_dump($modelList);exit;
                        foreach ($modelList as $modelRow){
                            $master[] = $modelRow->save();
//                            var_dump($modelRow->getErrors());
                        }
                        
                        if ($this->isActiveRecordUnique($uniqueAttributes)) {
                                  $importedPks[] = $model->primaryKey;
                        }    
                        if (!in_array(FALSE, $master)) {
                            $trans->commit();
                            $count++;
                        } 
                        else {
                            $trans->rollback();
                            $message='';
                            foreach($model->getErrors() as $errorkey => $value){
                                $message.=$value[0].'<br>';
                            }
                            foreach($bmcModel->getErrors() as $errorkey => $value){
                                $message.=$value[0].'<br>';
                            }                            
                            if($class == 'app\modules\organisation\models\PlantImport') {
                                foreach($mccModel->getErrors() as $errorkey => $value){
                                $message.=$value[0].'<br>';
                                }
                            }
                            return ['total'=>0,'status'=>'error','pk'=>0,'msg'=>'There is error in Record No : '.$key.'<br>'.$message];
                        }
                     }else{
                        $message='';
                        foreach($model->getErrors() as $errorkey => $value){
                            $message.=$value[0].'<br>';
                        }
                        return ['total'=>0,'status'=>'error','pk'=>0,'msg'=>'There is error in Record No : '.$key.'<br>'.$message];
                     }
                    
                    } catch (UserException $e) {
                            $trans->rollback();
                            return ['total'=>0,'status'=>'error','msg'=>$e->getMessage(),'pk'=>0];
                    }
            }
//            exit;
                
        }
        if ($count==count($data)-1) {
            return ['total'=>count($importedPks),'status'=>'success','msg'=>'Among '.count($importedPks).' records,'.count($importedPks).' records have been processed.','pk'=>count($importedPks)/*,'error'=>$errors*/];              
        }
    }
}