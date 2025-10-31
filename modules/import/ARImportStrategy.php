<?php

namespace app\modules\import;

use app\modules\reil\reil;
use ruskid\csvimporter\ARImportStrategy as CsvimporterARImportStrategy;
use yii;
use yii\base\Exception;
use ruskid\csvimporter\ImportInterface;
use ruskid\csvimporter\BaseImportStrategy;
use yii\base\UserException;
use yii\widgets\ActiveForm;

class ARImportStrategy extends CsvimporterARImportStrategy {

    /**
     * ActiveRecord class name
     * @var string
     */
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
    public $saveDeleteChild = '';
    public $unlinkFile = '';

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
        $errors = [];
        $count = 0;

        $orgCode = Yii::$app->session->get('organizations_code');
        $data = array_filter($data, function ($var) {
            return !empty($var[0]) && !is_null($var);
        });
        foreach ($data as $key => $row) {
            $skipImport = isset($this->skipImport) ? call_user_func($this->skipImport, $row) : false;
            if ($key == 0)
                continue;

            if (!$skipImport) {
                $trans = \Yii::$app->db->beginTransaction();
                /* @var $model \yii\db\ActiveRecord */
                $modelList = [];
                $deleteModelList = [];
                $unlink_files = [];
                $attachments = [];
                $masterdoc = [];

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
                        $addedAttributes[] = $config['attribute'];

                        //Set value to the model
                        $model->setAttribute($config['attribute'], trim($value));
                    } else if (property_exists($model, $config['attribute'])) {
                        //set value to virtual attribute
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
                $findField = isset($this->details['update_key']) ? $this->details['update_key'] : '';
                $excludeField = isset($this->details['exclude_update']) ? $this->details['exclude_update'] : '';

                if (!empty($this->details['setKeyFromExist']) && !empty($findField)) {
                    $tempModel = $model;
                    ActiveForm::validate($tempModel);
                    $findFields = explode(',', $findField);
                    foreach ($findFields as $val) {
                        $where[$val] = $tempModel->$val;
                    }
                    $existData = $model::find()->where($where)->one();
                    if (!empty($existData)) {
                        $updateKeyOfExistData = $this->details['setKeyFromExist'];
                        $updateKeyOfExistDataArr = explode(',', $updateKeyOfExistData);
                        foreach ($updateKeyOfExistDataArr as $updateKeyOfExistDataArrKey) {
                            $model->{$updateKeyOfExistDataArrKey} = $existData->{$updateKeyOfExistDataArrKey};
                        }
                    }
                }

                $error = ActiveForm::validate($model);

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
                        $scenario = $model->scenario;
                        $model = $existData;
                        $model->scenario = $scenario;
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
                if (isset($this->saveChild) && $this->saveChild && empty($model->getErrors()) && $model->validate()) {
                    $model->setChildTable($model, $modelList, $errors);
                }

                if (isset($this->saveDeleteChild) && $this->saveDeleteChild && empty($model->getErrors()) && $model->validate()) {
                    $model->setChildTableSaveDelete($model, $modelList, $deleteModelList, $unlink_files, $attachments, $masterdoc, $errors);
                }

                if (empty($model->getErrors()) && $model->validate() && empty($errors)) {
                    if (!empty($model->auto_key_config)) {
                        $model->save();
                    } else {
                        //  $modelList[] = $model;
                        $master[] = $model->save();
                    }
                    foreach ($modelList as $modelRow) {
                        if (!empty($model->auto_key_config)) {
                            $m_name = $modelRow::className();
                            $m_name = explode("\\", $m_name);
                            $m_name = $m_name[count($m_name) - 1];
                            if (!empty($model->auto_key_config[$m_name])) {
                                foreach ($model->auto_key_config[$m_name] as $key_config) {
                                    $modelRow->{$key_config['self_key']} = $model->{$key_config['parent_key']};
                                }
                            }
                        }
                        $master[] = $modelRow->save();
                    }

                    foreach ($deleteModelList as $modelRow) {
                        $master[] = $modelRow->delete();
                    }

                    if (!in_array(FALSE, $master)) {
                        $trans->commit();
                        $count++;
                        if (isset($this->unlinkFile) && $this->unlinkFile && isset($this->saveDeleteChild) && $this->saveDeleteChild) {
                            $model->moveFiles($unlink_files, $attachments, $masterdoc);
                        }
                    } else {
                        $trans->rollback();
                        $message = '';
                        foreach ($model->getErrors() as $errorkey => $value) {
                            $val = is_array($value) ? $value[0] : $value;
                            $message .= $val . '<br/>';
                        }
                        return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '-' . $message];
                    }
                } else {
                    $message = '';
                    foreach ($model->getErrors() as $errorkey => $value) {
                        $val = is_array($value) ? $value[0] : $value;
                        $message .= $val;
                    }
                    foreach ($errors as $array) {
                        foreach ($array as $errorkey => $value) {
                            $val = is_array($value) ? $value[0] : $value;
                            $message .= $val . '<br/>';
                        }
                    }
                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '-' . $message];
                }

                //Check if model is unique and saved with success
                if ($this->isActiveRecordUnique($uniqueAttributes)) {
                    $importedPks[] = $model->primaryKey;
                }
            }
        }
        if ($count == count($data) || $count == count($data) - 1) {
            return ['total' => count($importedPks),
                'status' => 'success',
                'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.',
                'pk' => count($importedPks)];
        }
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
