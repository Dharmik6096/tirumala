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
class CustomARImportStrategy extends BaseImportStrategy implements ImportInterface {

    /**
     * ActiveRecord class name
     * @var string
     */
    public $className;
    public $defaultFields;
    public $insert = 1;
    public $isIncrement = 0;
    public $scenario;
    public $required;
    public $type;
    public $updateField = '';
    public $historyClass = '';
    public $multiple = [
        'className',
        'config',
        'defaultFields'
    ];
    public $old_att = array('dcs', 'animal_type_code');
    public $change_att = array('society', 'milk_type_code');
    
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
        $request = Yii::$app->request->queryParams;
        $importedPks = [];
        $errors = [];
        $count = 0;
        $required = !empty($this->required) ? explode(',', $this->required) : [];
        $selected = !empty($request['selected']) ? explode(',', $request['selected']) : [];
        $orgCode = Yii::$app->session->get('organizations_code');

        $data = array_filter($data, function($var) {
            return !empty($var[0]) && !is_null($var);
        });
        $data = array_filter($data);

        foreach ($data as $key => $row) {

            $skipImport = isset($this->skipImport) ? call_user_func($this->skipImport, $row) : false;

            if ($key == 0)
                continue;

            if (!$skipImport) {
                /* @var $model \yii\db\ActiveRecord */
                $model = new $this->className;
                if (!empty($this->scenario))
                    $model->scenario = $this->scenario;

                $uniqueAttributes = [];
                $addedAttributes = [];
                foreach ($this->configs as $config) {
                    $config['attribute'] = str_replace($this->change_att, $this->old_att, $config['attribute']);
                    if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                        
                        $config['attribute'] = str_replace($this->change_att, $this->old_att, $config['attribute']);
                        $value = call_user_func($config['value'], $row);

                        //Create array of unique attributes
                        if (isset($config['unique']) && $config['unique']) {
                            $uniqueAttributes[$config['attribute']] = trim($value);
                        }
                        $addedAttributes [] = $config['attribute'];

                        //Set value to the model
                        $model->setAttribute($config['attribute'], trim($value));
                    }
                }
                if (isset($this->defaultFields) && $this->type != 'update') {
                    foreach ($this->defaultFields as $config) {

                        if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                            //Create array of unique attributes
                            if (isset($config['unique']) && $config['unique']) {
                                $uniqueAttributes[$config['attribute']] = trim($config['value']);
                            }

                            //Set value to the model
                            $model->setAttribute($config['attribute'], trim($config['value']));
                        }
                    }
                }
                $primaryKey = $model->tableSchema->primaryKey[0];
                if ($this->type != 'update') {
                    if ($this->isIncrement == 1) {
                        $model->{$primaryKey} = \Yii::$app->general->getCodeAutoIncrement($model);
                    } else if (method_exists($model, 'getCode')) {
                        $model->{$primaryKey} = $model->getCode();
                    }
                }
                $modelNew = [];

                if (!empty($this->updateField) && $this->type == 'update') {
                    $className = $this->className;
                    $modelNew = $className::find()->where([$this->updateField => $model->{$this->updateField}])->one();
                }
                if ($modelNew) {
                    $history = !empty($this->historyClass) ? $this->historyClass : $this->className . 'History';
                    if (is_object($history) || class_exists($history)) {
                        $historyModel = is_object($history) ? $history : new $history;
                        \Yii::$app->operation->history($modelNew, $historyModel, 'UPDATE');
                        $historyModel->save();
                    }
                    $Key = $modelNew->{$primaryKey};
                    foreach ($addedAttributes as $row) {
                        $modelNew->{$row} = trim($model->{$row});
                    }
                    $modelNew->{$primaryKey} = $Key;
                    $modelNew->scenario = $this->scenario;
                    $model = $modelNew;
                }

                $error = ActiveForm::validate($model);
                if (!empty($required)) {
                    foreach ($required as $req) {
                        $req = trim($req);
                        $req = str_replace($this->change_att, $this->old_att, $req);
                        if (empty($model->{$req})) {
                            $req = str_replace($this->old_att, $this->change_att, $req);
                            $nm = ucwords(str_replace('_', ' ', $req));
                            $model->addError($req, $nm . ' can not be blank');
                        }
                    }
                }
                if ($this->type == 'update' && empty($modelNew)) {
                    $nm = ucwords(str_replace('_', ' ', $this->updateField));
                    $model->addError($this->updateField, $nm . ' does not exist');
                }
                if ($model->hasAttribute('is_active') && in_array('is_active', $selected)) {
                        $nm = ucwords(str_replace('_', ' ', 'is_active'));
                        if(!(preg_match('/^[0-9]*$/', $model->is_active))){
                            $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
                        }
                        if($model->is_active != 0 && $model->is_active != 1){
                            $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
                        }
                    }
//                if ($model->hasAttribute('is_active')) {
//                    if(in_array('is_active', $selected)){
//                        if($model->is_active != 0 || $model->is_active != 1){
//                            $nm = ucwords(str_replace('_', ' ', 'is_active'));
//                            $model->addError($model->is_active, $nm . ' must have 0 or 1 value');
//                        }
//                    }
//                }
                if (empty($model->getErrors()) && $model->save()) {
                    $count++;
                } else {
                    $message = '';
                    foreach ($model->getErrors() as $errorkey => $value) {
                        $message.=$value[0] . '<br>';
                    }
                    return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message/* ,'error'=>$errors */];
                }

                if ($this->isActiveRecordUnique($uniqueAttributes)) {
                    $importedPks[] = $model->primaryKey;
                }
            }
        }

        if ($count == count($data) - 1) {
            return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)/* ,'error'=>$errors */];
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

}
