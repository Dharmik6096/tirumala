<?php

/**
 * @copyright Copyright Victor Demin, 2015
 * @license https://github.com/ruskid/yii2-csv-importer/LICENSE
 * @link https://github.com/ruskid/yii2-csv-importer#README
 */

namespace ruskid\csvimporter;

use yii;
use yii\base\Exception;
use ruskid\csvimporter\ImportInterface;
use ruskid\csvimporter\BaseImportStrategy;
use yii\base\UserException;
//use app\models\IdentityMaster;
use yii\widgets\ActiveForm;
/**
 * Import from CSV. This will create/validate/save an ActiveRecord object per excel line.
 * This is the slowest way to insert, but most reliable. Use it with small amounts of data.
 *
 * @author Victor Demin <demin@trabeja.com>
 */
class ARImportStrategy extends BaseImportStrategy implements ImportInterface {

    /**
     * ActiveRecord class name
     * @var string
     */
    public $className;
    public $defaultFields;
    public $insert=1;
    public $isIncrement=0;
    public $scenario;
    public $updateField='';
    public $multiple=[
        'className',
        'config',
        'defaultFields'
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
        $count=0;
        
//        $trans = \Yii::$app->db->beginTransaction();
//        try {
//            $identity = new IdentityMaster();
//            $idenRecord = $identity->getIdentity();
            $orgCode = Yii::$app->session->get('organizations_code');;
            
            //$abc = array_map('array_filter', $data);
            $data = array_filter($data, function($var){  return !empty($var[0]) && !is_null($var);} );
            $data = array_filter($data);
            
            foreach ($data as $key=>$row) {              
                
                $skipImport = isset($this->skipImport) ? call_user_func($this->skipImport, $row) : false;

                if($key==0)
                    continue;

                if (!$skipImport) {
                    /* @var $model \yii\db\ActiveRecord */
                    $model = new $this->className;
                    if(!empty($this->scenario))
                        $model->scenario = $this->scenario;
                    
                    $uniqueAttributes = [];
                    $addedAttributes = [];
                    foreach ($this->configs as $config) {
                    if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                        $value = call_user_func($config['value'], $row);

                        //Create array of unique attributes
                        if (isset($config['unique']) && $config['unique']) {
                            $uniqueAttributes[$config['attribute']] = trim($value);
                        }
                        $addedAttributes [] = $config['attribute'];

                        //Set value to the model
                        $model->setAttribute($config['attribute'], trim($value));
                    } else if (property_exists($model, $config['attribute'])) {
                        //Set value to the model of public attribute
                        $value = call_user_func($config['value'], $row);
                        $model->{$config['attribute']} = $value;
                    }
                }
                    /*$modelNew = [];
                    $primaryKey = $model->tableSchema->primaryKey[0];
                    if(!empty($this->updateField)){
                        $className = $this->className;
                        $modelNew = $className::find()->where([$this->updateField=>$model->{$this->updateField},'language_code'=>$model->language_code])->one();
                    }
                    if($modelNew){
                        $history = $this->className.'History';
                        $historyModel = new $history;
                        \Yii::$app->operation->history($modelNew, $historyModel, 'UPDATE');
                        
                        $Key = $modelNew->{$primaryKey};
                        $modelNew->{$primaryKey} = $Key;
                        foreach ($addedAttributes as $row){
                            $modelNew->{$row} = trim($model->{$row});
                        }
                        $model = $modelNew;
                    }else{
                        if ($model->hasAttribute('local_code')){
                            $code = \Yii::$app->general->getLocalCode($model->tableName());
                            $incrNo = substr($code, (strlen($idenRecord->organization_code)+1));
                            $model->local_code = $orgCode.'-'.$incrNo;
                            $incrNo=$incrNo+1;
                        }else if($this->isIncrement==1){
                            $model->{$primaryKey} = \Yii::$app->general->getCodeAutoIncrement($model);
                        }
                    }*/
                   /*foreach ($this->defaultFields as $config) {

                        if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                           // $value = call_user_func($config['value'], $row);

                            //Create array of unique attributes
                            if (isset($config['unique']) && $config['unique']) {
                                $uniqueAttributes[$config['attribute']] = trim($config['value']);
                            }

                            //Set value to the model
                            $model->setAttribute($config['attribute'], trim($config['value']));
                        }

                    }*/
                    if(isset($this->defaultFields)){
                        foreach ($this->defaultFields as $default) {
                            if (isset($default['attribute']) && $model->hasAttribute($default['attribute'])) {
                                $value = $default['value'];
                                //Set value to the model
                                $model->setAttribute($default['attribute'], $value);
                            }
                        }
                    }
                 
                    $primaryKey = $model->tableSchema->primaryKey[0];
                    if($this->isIncrement==1){
                        $model->{$primaryKey} = \Yii::$app->general->getCodeAutoIncrement($model);
                    } else if (method_exists($model, 'getCode')) {
                        $model->{$primaryKey} = $model->getCode();
                    }
//                    print_r($model);
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
                    if(empty($model->getErrors()) && $model->save()){
                        $count++;
                    }else{                    
                        $message='';
                        foreach($model->getErrors() as $errorkey => $value){
                            $message.=$value[0].'<br>';
                        }
                        return ['total'=>0,'status'=>'error','pk'=>0,'msg'=>'There is error in Record No : '.$key.'<br>'.$message/*,'error'=>$errors*/];
                    }  

                    if ($this->isActiveRecordUnique($uniqueAttributes)) {
                              $importedPks[] = $model->primaryKey;
                    }                   
                  }
            }
       
            if ($count==count($data)-1) {
                return ['total'=>count($importedPks),'status'=>'success','msg'=>'Among '.count($importedPks).' records,'.count($importedPks).' records have been processed.','pk'=>count($importedPks)/*,'error'=>$errors*/];              
            }
//        } catch (UserException $e) {
//            $trans->rollback();
//            return ['total'=>0,'status'=>'error','msg'=>$e->getMessage(),'pk'=>0/*,'error'=>$errors*/];
////            echo $e->getMessage();
//        }
//        return ['total'=>count($data),'pk'=>$importedPks/*,'error'=>$errors*/];
    }

    /**
     * Will check if Active Record is unique by exists query.
     * @param array $attributes
     * @return boolean
     */
    protected function isActiveRecordUnique($attributes) {
        /* @var $class \yii\db\ActiveRecord */
        $class = $this->className;
        return empty($attributes) ? true :
                !$class::find()->where($attributes)->exists();
    }

    private function setFields($fields,$data){
        
    }

}
