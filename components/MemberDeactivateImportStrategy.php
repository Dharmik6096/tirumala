<?php

namespace app\components;

use ruskid\csvimporter\ARImportStrategy;
use yii\widgets\ActiveForm;
use Yii;
use app\modules\dcsoperation\models\TblMemberDeactive;
use app\modules\organisation\models\TblDcs;

class MemberDeactivateImportStrategy extends ARImportStrategy {

    public $scenario;
    public $defaultFields;

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
                    $model = new $this->className;
                    if (!empty($this->scenario)) {
                        $model->scenario = $this->scenario;
                    }
                    $addedAttributes = [];
                    foreach ($this->configs as $field) {
                        $value = call_user_func($field['value'], $row);
                        if (isset($field['attribute']) && ($model->hasAttribute($field['attribute']))) {
                            //Set value to the model
                            ($model->hasAttribute($field['attribute'])) ? $model->setAttribute($field['attribute'], $value) : '';
                            $addedAttributes[$field['attribute']] = $field['attribute'];
                        } else if (property_exists($model, $field['attribute'])) {
                            //Set value to the model of public attribute
                            $model->{$field['attribute']} = $value;
                            $addedAttributes[$field['attribute']] = $field['attribute'];
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
                    $dcs = new TblDcs();
                    $model->dcs_code = $dcs->getValidDcs($model->dcs_code);
                    $model->member_code = $model->dcs_code . str_pad(substr($model->member, -4), 4, '0', STR_PAD_LEFT);

                    if ($model->is_active == '1') {
                        $checkExistData = TblMemberDeactive::find()
                                ->where(['dcs_code' => $model->dcs_code, 'member_code' => $model->member_code])
                                ->andWhere(['IS NOT', 'from_date', NULL])
                                ->andWhere(['IS', 'to_date', NULL])
                                ->one();
                        if (!empty($checkExistData)) {
                            foreach ($addedAttributes as $row) {
                                $checkExistData->{$row} = $model->{$row};
                            }
                            $checkExistData->scenario = $model->scenario;
                            $model = $checkExistData;
                        } else {
                            $count++;
                            $trans->rollback();
                            continue;
                        }
                    } else {
                        $primaryKey = $model->tableSchema->primaryKey[0];
                        $model->{$primaryKey} = \Yii::$app->general->getCodeAutoIncrement($model);
                    }

                    if (empty($model->getErrors()) && $model->validate()) {
                        $master[] = $model->save(TRUE, FALSE);
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
        if ($count == count($data) - 1) {
            return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)];
        }
    }

}
