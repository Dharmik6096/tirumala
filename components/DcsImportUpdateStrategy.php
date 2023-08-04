<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use app\modules\import\ARImportStrategy;
use app\modules\organisation\models\TblDcsVillageMapping;
use app\modules\organisation\models\TblSocietyCodes;
use yii\widgets\ActiveForm;
use Yii;
use app\modules\organisation\models\TblDcsHistory;
use yii\base\UserException;

class DcsImportUpdateStrategy extends ARImportStrategy {

    public function import(&$data) {
        $importedPks = [];
        $errors = [];
        $count = 0;


        //$abc = array_map('array_filter', $data);
        $data = array_filter($data, function($var) {
            return !empty($var[0]) && !is_null($var);
        });
        $data = array_filter($data);

        foreach ($data as $key => $row) {

            $skipImport = isset($this->skipImport) ? call_user_func($this->skipImport, $row) : false;

            if ($key == 0)
                continue;

            if (!$skipImport) {
                $trans = \Yii::$app->db->beginTransaction();
                try {
                    /* @var $model \yii\db\ActiveRecord */
                    $model = new $this->className;
                    if (!empty($this->scenario))
                        $model->scenario = 'customImportUpdate';

                    $uniqueAttributes = [];
                    $this->setAttributes($this->configs, $model, $row);


                    $attributes = $model->getAttributes();
                    $modelList = [];
                    $modelData = $model->findOne($model->dcs_code);
                    if (!empty($modelData)) {
                        $model = $modelData;
                        $model->setModel();
                        $model->scenario = 'customImportUpdate';
                        $historyModel = new TblDcsHistory();
                        Yii::$app->operation->history($model, $historyModel, UPDATE);
                        array_push($modelList, $historyModel);
                        $this->setAttributes($this->configs, $model, $row);
                        $error = ActiveForm::validate($model);
                    } else {
                        $model->addError($model->dcs_code, 'Please enter valid DCS Code.');
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
//                    array_push($modelList, $model);

                    if (empty($model->getErrors()) && $model->validate()) {
                        array_push($modelList, $model);
                        if (!empty($model->village_code)) {
                            $modelMapping = new TblDcsVillageMapping();
                            $modelMappingData = $modelMapping->getRecord($model->dcs_code, $model->village_code);
                            if (empty($modelMappingData)) {
                                $modelMapping->dcs_code = $model->dcs_code;
                                $modelMapping->village_code = $model->village_code;
                                $modelMapping->is_active = isset($model->is_active) && $model->is_active != NULL ? $model->is_active : 1;
                                array_push($modelList, $modelMapping);
                            }
                        }
                        $modelCodes = new TblSocietyCodes();
                        $modelCodes->dcs_code = $model->dcs_code;
                        $modelCodesData = $modelCodes->getRecord();
                        if (empty($modelCodesData)) {
                            $modelCodes->bipl_code = substr($model->dcs_code, 2, 8);
                            $modelCodes->union_code = $model->union_code;
                            $modelCodes->bmc_code = NULL;
                            array_push($modelList, $modelCodes);
                        }

                        foreach ($modelList as $modelRow) {
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
                            $message = '';
                            foreach ($model->getErrors() as $errorkey => $value) {
                                $message .= $value[0] . '<br>';
                            }
                            return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                        }
                    } else {
                        $message = '';
                        foreach ($model->getErrors() as $errorkey => $value) {
                            $message .= $value[0] . '<br>';
                        }
                        return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                    }

//                    $modelMapping = new TblDcsVillageMapping();
//                    $modelMapping->dcs_code = $model->dcs_code;
//                    $modelMapping->village_code = $model->village_code;
//                    $modelMapping->is_active =$model->is_active;
//                    array_push($modelList, $modelMapping);
//                                      
//                    foreach ($modelList as $modelRow){
//                        $master[] = $modelRow->save();
//                    }
//                 
//                    if ($this->isActiveRecordUnique($uniqueAttributes)) {
//                              $importedPks[] = $model->primaryKey;
//                    }    
//                    if (!in_array(FALSE, $master)) {
//                        $trans->commit();
//                        $count++;
//                    } else {
//                        $trans->rollback();
//                        $message='';
//                        foreach($model->getErrors() as $errorkey => $value){
//                            $message.=$value[0].'<br>';
//                        }
//                        return ['total'=>0,'status'=>'error','pk'=>0,'msg'=>'There is error in Record No : '.$key.'<br>'.$message];
//                    }
                } catch (UserException $e) {
                    $trans->rollback();
                    return ['total' => 0, 'status' => 'error', 'msg' => $e->getMessage(), 'pk' => 0];
                }
            }
        }
        if ($count == count($data) || $count == count($data) - 1) {
            return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)/* ,'error'=>$errors */];
        }
    }

    public function setAttributes($configs, &$model, $row) {
        foreach ($configs as $config) {
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
    }

}
