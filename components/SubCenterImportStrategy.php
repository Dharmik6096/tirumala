<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\components;

use yii\base\Exception;
use ruskid\csvimporter\ImportInterface;
use ruskid\csvimporter\BaseImportStrategy;
use app\modules\import\ARImportStrategy;
use app\modules\organisation\models\TblSubCenter;
use app\modules\organisation\models\TblCollectionPoint;
use app\modules\organisation\models\TblDcsVillageMapping;
use yii\widgets\ActiveForm;

class SubCenterImportStrategy extends ARImportStrategy{
    
    public function import(&$data) {
        $importedPks = [];
        $errors=[];
        $count=0;
        
           
            $orgCode = Yii::$app->session->get('organizations_code');
            
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
                    
                    foreach ($this->defaultFields as $config) {

                        if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                           // $value = call_user_func($config['value'], $row);

                            //Create array of unique attributes
                            if (isset($config['unique']) && $config['unique']) {
                                $uniqueAttributes[$config['attribute']] = $config['value'];
                            }

                            //Set value to the model
                            $model->setAttribute($config['attribute'], $config['value']);
                        }

                    }
                    $modelList = [];
                    $error = ActiveForm::validate($model);
                    array_push($modelList, $model);
                    
                    $list = $model->setCollectionPoint($model, 'I', $this->scenario);
                    
                    foreach ($list as $row){
                        array_push($modelList, $row);
                    }
                   
                    foreach ($modelList as $modelRow){
                        $master[] = $modelRow->save();
                    }
                 
                    if ($this->isActiveRecordUnique($uniqueAttributes)) {
                              $importedPks[] = $model->primaryKey;
                    }    
                    if (!in_array(FALSE, $master)) {
                        $trans->commit();
                        $count++;
                    } else {
                        $trans->rollback();
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
                
        }
        if ($count==count($data)-1) {
            return ['total'=>count($importedPks),'status'=>'success','msg'=>'Among '.count($importedPks).' records,'.count($importedPks).' records have been processed.','pk'=>count($importedPks)/*,'error'=>$errors*/];              
        }
    }
}