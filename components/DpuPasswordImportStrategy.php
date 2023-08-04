<?php

namespace app\components;

use app\modules\import\ARImportStrategy;
use yii\widgets\ActiveForm;
use Yii;
use app\modules\setting\models\TblDpuPasswords;
use app\modules\setting\models\TblDPUPasswordsHistory;
use yii\base\UserException;

class DpuPasswordImportStrategy extends ARImportStrategy {

    public function import(&$data) {
        $importedPks = [];
        $importedData = [];
        $errors = [];
        $count = 0;

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
                    $modelList = [];
                    $model = [];
                    $model = new $this->className;
                    if (!empty($this->scenario)) {
                        $model->scenario = $this->scenario;
                    }
                    $addedAttributes = [];
                    foreach ($this->configs as $config) {
                        $value = call_user_func($config['value'], $row);
                        if (isset($config['attribute']) && ($model->hasAttribute($config['attribute']))) {
                            //Set value to the model
                            ($model->hasAttribute($config['attribute'])) ? $model->setAttribute($config['attribute'], $value) : '';
                            $addedAttributes[$config['attribute']] = $config['attribute'];
                        } else if (property_exists($model, $config['attribute'])) {
                            //Set value to the model of public attribute
                            $model->{$config['attribute']} = $value;
                            $addedAttributes[$config['attribute']] = $config['attribute'];
                        }
                    }
                    $existData = TblDpuPasswords::find()->where(['dcs_code' => $model->dcs_code])->one();
                    if (!empty($existData)) {
                        if (($model->AdminPwd != $existData->AdminPwd || $model->SuperPwd != $existData->SuperPwd || $model->UserPwd != $existData->UserPwd)) {
                            $historyModel = new TblDPUPasswordsHistory();
                            \Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                            $modelList[] = $historyModel;
                            foreach ($addedAttributes as $row) {
                                $existData->{$row} = $model->{$row};
                            }
                            $existData->scenario = $model->scenario;
                            $model = $existData;
                        } else {
                            $model = $existData;
                        }
                    }
                    $error = ActiveForm::validate($model);
                    if (empty($model->getErrors()) && $model->validate()) {
                        $modelList[] = $model;
                        foreach ($modelList as $modelRow) {
                            $master[] = $modelRow->save();
                        }
                        if (!in_array(FALSE, $master)) {
                            $trans->commit();
                            $count++;
                            $importedPks[] = $model->primaryKey;
                        } else {
                            $trans->rollback();
                            $message = '';
                            foreach ($model->getErrors() as $errorkey => $value) {
                                $message .= $value[0] . '<br/>';
                            }
                            return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                        }
                    } else {
                        $message = '';
                        foreach ($model->getErrors() as $errorkey => $value) {
                            $message .= $value[0] . '<br/>';
                        }

                        return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                    }
                } catch (UserException $e) {
                    $trans->rollback();
                    return ['total' => 0, 'status' => 'error', 'msg' => $e->getMessage(), 'pk' => 0];
                }
            }
        }
        if ($count == count($data) || $count == count($data) - 1) {
            return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)];
        }
    }

}
