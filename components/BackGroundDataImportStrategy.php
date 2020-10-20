<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use ruskid\csvimporter\ARImportStrategy;
use yii\widgets\ActiveForm;
use Yii;

class BackGroundDataImportStrategy extends ARImportStrategy {

    public $className;
    public $defaultFields;
    public $insert = 1;
    public $isIncrement = 0;
    public $scenario;
    public $updateField = '';
    public $multiple = [
        'className',
        'config',
        'defaultFields'
    ];
    public $saveChild = '';
    public $details;
    public $file_path, $file_name;

    public function import(&$data) {

        $importedPks = [];
        $errors = [];
        $error_lines = [];
        $count = 0;
        $total_cnt = 0;
        $message = '';

//        $trans = \Yii::$app->db->beginTransaction();
//        try {
//            $identity = new IdentityMaster();
//            $idenRecord = $identity->getIdentity();
        $orgCode = Yii::$app->session->get('organizations_code');

        //$abc = array_map('array_filter', $data);
        $data = array_filter($data, function($var) {
            return !empty($var[0]) && !is_null($var);
        });
        $data = array_filter($data);

        foreach ($data as $key => $row) {
            $master = [];

            $skipImport = isset($this->skipImport) ? call_user_func($this->skipImport, $row) : false;

            if ($key == 0)
                continue;

            if (!$skipImport) {
                $trans = \Yii::$app->db->beginTransaction();
                /* @var $model \yii\db\ActiveRecord */
                $modelList = [];
                $model = new $this->className;
                if (!empty($this->scenario))
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

                if (isset($this->defaultFields)) {
                    foreach ($this->defaultFields as $default) {
                        if (isset($default['attribute']) && $model->hasAttribute($default['attribute'])) {
                            $value = $default['value'];
                            //Set value to the model
                            $model->setAttribute($default['attribute'], $value);
                        }
                    }
                }
                $primaryKey = $model->tableSchema->primaryKey[0];
                $error = ActiveForm::validate($model);

                $findField = isset($this->details['update_key']) ? $this->details['update_key'] : '';
                $excludeField = isset($this->details['exclude_update']) ? $this->details['exclude_update'] : '';
                if (!empty($findField)) {
                    $findFields = explode(',', $findField);
                    foreach ($findFields as $val) {
                        $where[$val] = $model->$val;
                    }
                    $existData = $model::find()->where($where)->one();
                    if (!empty($existData) && !empty($excludeField)) {
                        $excludes = [];
                        $exclude = explode(',', $excludeField);
                        foreach ($exclude as $val) {
                            $excludes[] = $val;
                        }
                        $model = $existData;
                        $model->scenario = 'importCsv';
                        $history = !empty($this->details['historyClass']) ? $this->details['historyClass'] : NULL;
                        if (!empty($history)) {
                            $history = Yii::$app->path->define($history);
                        }
                        if (is_object($history) || class_exists($history)) {
                            $historyModel = is_object($history) ? $history : new $history();
                            \Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                            array_push($modelList, $historyModel);
                        }
                        $this->setAttributes($this->configs, $model, $row, $excludes);
                    } else {
                        if ($this->isIncrement == 1) {
                            $model->{$primaryKey} = \Yii::$app->general->getCodeAutoIncrement($model);
                        } else if (method_exists($model, 'getCode')) {
                            $model->{$primaryKey} = $model->getCode();
                        }
                    }
                } else {
                    if ($this->isIncrement == 1) {
                        $model->{$primaryKey} = \Yii::$app->general->getCodeAutoIncrement($model);
                    } else if (method_exists($model, 'getCode')) {
                        $model->{$primaryKey} = $model->getCode();
                    }
                }
                if ($model->hasAttribute('is_active')) {
                    $nm = ucwords(str_replace('_', ' ', 'is_active'));
                    if (!(preg_match('/^[0-9]*$/', $model->is_active))) {
                        $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
                    }
                    if ($model->is_active != 0 && $model->is_active != 1) {
                        $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
                    }
                }
                $errors = [];
                if (isset($this->saveChild) && $this->saveChild && $model->validate()) {
                    $model->setChildTable($model, $modelList, $errors);
                }
                if (empty($model->getErrors()) && $model->validate() && empty($errors)) {
                    $modelList[] = $model;

                    foreach ($modelList as $modelRow) {
                        $master[] = $modelRow->save();
                    }
                    if (!in_array(FALSE, $master)) {
                        $trans->commit();
                        $count++;
                    } else {
                        $trans->rollback();
                        $message = '';
                        foreach ($model->getErrors() as $errorkey => $value) {
                            $message .= $value[0] . '-';
                        }
                        $row['response_message'] = $message;
                        $error_lines[] = $row;
//                         return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                    }
                } else {
                    $trans->rollback();
                    $message = '';
                    foreach ($model->getErrors() as $errorkey => $value) {
                        $message .= $value[0] . '-';
                    }

                    foreach ($errors as $array) {
                        foreach ($array as $errorkey => $value) {
                            $message .= $value[0] . '-';
                        }
                    }
                    $row['response_message'] = $message;
                    $error_lines[] = $row;

//                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message/* ,'error'=>$errors */];
                }

                if ($this->isActiveRecordUnique($uniqueAttributes)) {
                    $importedPks[] = $model->primaryKey;
                }
            }
        }
        $total_cnt = count($data) - 1;
        return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message, 'error_lines' => $error_lines, 'total_cnt' => $total_cnt];

        if ($count == count($data) - 1) {
            return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)/* ,'error'=>$errors */];
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

    private function setFields($fields, $data) {
        
    }

    public function setAttributes($configs, &$model, $row, $excludes = []) {
        foreach ($configs as $config) {
            if (isset($config['attribute']) && !in_array($config['attribute'], $excludes)) {
                $value = call_user_func($config['value'], $row);
                if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                    //Create array of unique attributes
                    if (isset($config['unique']) && $config['unique']) {
                        $uniqueAttributes[$config['attribute']] = $value;
                    }
                    //Set value to the model
                    ($model->hasAttribute($config['attribute'])) ? $model->setAttribute($config['attribute'], $value) : '';
                    $addedAttributes[$config['attribute']] = $config['attribute'];
                } else if (property_exists($model, $config['attribute'])) {
                    //Set value to the model of public attribute
                    $model->{$config['attribute']} = $value;
                    $addedAttributes[$config['attribute']] = $config['attribute'];
                }
            }
        }
    }

}
