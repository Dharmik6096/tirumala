<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\components;

use yii\base\Exception;
use ruskid\csvimporter\ImportInterface;
use ruskid\csvimporter\BaseImportStrategy;

class MultipleImport extends BaseImportStrategy implements ImportInterface {

    /**
     * ActiveRecord class name
     * @var string
     */
    public $className;
    public $defaultFields;
    
    public $childClassName;
    public $childFieldsName=[];
    public $isChildImport = false;
    public $multiple=[
        'childClassName',
        'childFieldsName',
        'childDefaultFields'
    ];

    /**
     * @throws Exception
     */
    public function __construct() {
        $arguments = func_get_args();


        if (!empty($arguments)) {
            foreach ($arguments[0] as $key => $property) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $property;
                }
            }
        }

        if ($this->className === null) {
            throw new Exception(__CLASS__ . ' className is required.');
        }
        if ($this->configs === null) {
            throw new Exception(__CLASS__ . ' configs is required.');
        }
    }

    /**
     * Will multiple import data into table
     * @param array $data CSV data passed by reference to save memory.
     * @return array Primary keys of imported data
     */
    public function import(&$data) {

        $importedPks = [];
        $errors=[];
//        print_r($this->isChildImport);
//        exit;
        foreach ($data as $row) {
            
            $skipImport = isset($this->skipImport) ? call_user_func($this->skipImport, $row) : false;

            if (!$skipImport) {
                /* @var $model \yii\db\ActiveRecord */

//                print_r($this->multiple['childFieldsName']);exit;
                $model = new $this->className;
                
                $uniqueAttributes = [];
                
                $this->setFields2($this->configs, $model, $uniqueAttributes, 1, $row);

                 //$this->setFields($this->configs, $childModel, $uniqueAttributes, false, $row, $childModel ,1);
                
                $this->setFields2($this->defaultFields, $model, $uniqueAttributes,0);
                
                //Check if model is unique and saved with success
                if($model->save()){
                    $sentbox=new \app\models\TblSentbox();
                    if($sentbox->setSentbox($model, INSERT)){
                        $model->flg_sentbox_entry = SENTBOX_FLAG;
                        $model->save();
                    }

                   if ($this->isActiveRecordUnique($uniqueAttributes)) {

                        $importedPks[] = $model->primaryKey;
                    }

                   
                     if($this->isChildImport){
                         $childModel = new $this->multiple['childClassName'];
                         $this->setFields2($this->configs, $childModel, null,1 , $row, true);
                         $this->setFields2($this->multiple['childDefaultFields'], $childModel, null,0 , null, false);
                         if($childModel->save()){
                             $sentbox=new \app\models\TblSentbox();
                             if($sentbox->setSentbox($childModel, 'Insert')){
                                    $childModel->flg_sentbox_entry='Y';
                                    $childModel->save();
                             }
                         }
                     }
                }else{
                    $errors=$model->getErrors();
                }
            }
        }
        return ['total'=>count($data),'pk'=>$importedPks,'error'=>$errors];
    }

    private function setFields2( $fields, $model, $uniqueAttributes=null, $is_value=0, $row=null, $childEntry=false ){

//        print_r($fields);
        foreach ($fields as $config) {

//            if(!$childEntry){
                $condition = (isset($config['attribute']) && $model->hasAttribute($config['attribute']));
//            }else{
//                $condition = (isset($config['attribute']) && isset($this->multiple['childFieldsName'][$config['attribute']]) && $model->hasAttribute($this->multiple['childFieldsName'][$config['attribute']]));
//            }
//            echo $config['attribute'].'+';
            if ($condition) {
                if($is_value==1)
                    $value = call_user_func($config['value'], $row);
                else
                    $value = $config['value'];

//                echo $config['attribute'].'||'.$value.'-'.$this->multiple['childFieldsName'][$config['attribute']].'<br>';
                //Create array of unique attributes
                if (isset($config['unique']) && $config['unique']) {
                    $uniqueAttributes[$config['attribute']] = $value;
                }

//                Set value to the model
//                if(!$childEntry){
                    $model->setAttribute($config['attribute'], $value);
//                }else{
//                    $model->setAttribute($this->multiple['childFieldsName'][$config['attribute']], $value);
//                }
                
            }
        }

        return $uniqueAttributes;
    }

    private function setFields($fields,$model,$uniqueAttributes=null, $childDefaultEntry=false,$row=null,$childModel=null,$is_value=0){

        foreach ($fields as $config) {

            if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {

                if($is_value==1)
                    $value = call_user_func($config['value'], $row);
                else
                    $value = $config['value'];

                //Create array of unique attributes
                if (isset($config['unique']) && $config['unique'] && !$this->isChildImport) {
                    $uniqueAttributes[$config['attribute']] = $value;
                }

                //Set value to the model
                $model->setAttribute($config['attribute'], $value);

                if($this->isChildImport && !$childDefaultEntry){
                    if(isset($this->multiple['childFieldsName'][$config['attribute']]))
                    {
                        if (isset($config['attribute']) && $childModel->hasAttribute($this->multiple['childFieldsName'][$config['attribute']])) {
                            $childModel->setAttribute($this->multiple['childFieldsName'][$config['attribute']], $value);
                        }
                    }
                }
            }
        }

        return $uniqueAttributes;
    }

    /**
     * Will check if Active Record is unique by exists query.
     * @param array $attributes
     * @return boolean
     */
    private function isActiveRecordUnique($attributes) {
        /* @var $class \yii\db\ActiveRecord */
        $class = $this->className;
        return empty($attributes) ? true :
                !$class::find()->where($attributes)->exists();
    }

}

