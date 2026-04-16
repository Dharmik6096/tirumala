<?php

namespace app\components;

use Yii;
use app\models\ChildModel;
use ruskid\csvimporter\ARImportStrategy;
use app\modules\dcsoperation\models\TblMember;
use app\modules\dcsoperation\models\TblMemberHistory;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblBankDetailsHistory;

class BankVerificationUpdateStrategy extends ARImportStrategy {

    public function import(&$data) {
        $importedPks = [];
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
                    if (!empty($this->scenario))
                        $model->scenario = $this->scenario;

                    foreach ($this->configs as $config) {
                        $value = call_user_func($config['value'], $row);
                        if (isset($config['attribute']) && $model->hasAttribute($config['attribute'])) {
                            $model->setAttribute($config['attribute'], trim($value));
                        } else if (property_exists($model, $config['attribute'])) {
                            $model->{$config['attribute']} = $value;
                        }
                    }

                    $master = [];
                    $modelSave = [];

                    if ($model->validate()) {
                        if ($model->is_verified == '1') {
                            $map = [
                                'MEMBER' => [TblMember::class, ['member_code' => $model->code], TblMemberHistory::class],
                                'DCS' => [TblBankDetails::class, ['module_name' => 'society', 'module_code' => $model->code, 'is_default' => 1, 'is_active' => 1], TblBankDetailsHistory::class],
                                'CUSTOMER' => [TblBankDetails::class, ['module_name' => 'customer', 'module_code' => $model->code, 'is_default' => 1, 'is_active' => 1], TblBankDetailsHistory::class],
                            ];

                            [$class, $condition, $historyClass] = $map[$model->verify_for];
                            $modelData = $class::findOne($condition);

                            if (!empty($modelData) && $modelData->is_verified == '0') {
                                $historyModel = new $historyClass();
                                Yii::$app->operation->history($modelData, $historyModel, UPDATE);
                                $modelData->is_verified = $modelData->is_kyc_verified = $model->is_verified;
                                $modelSave[] = $historyModel;
                                $modelSave[] = $modelData;
                            }
                        }
                    } else {
                        $message = '';
                        foreach ($model->getErrors() as $errorkey => $value) {
                            $message .= $value[0] . '<br>';
                        }
                        return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                    }

                    foreach ($modelSave as $modelRow) {
                        $is_saved = $modelRow instanceof ChildModel ? $modelRow->save(true, false) : $modelRow->save(false);
                        $master[] = $is_saved;
                        if (!$is_saved) {
                            $errors[] = $modelRow->getErrors();
                        }
                    }

                    if (empty($master)) {
                        $master[] = true;
                    }

                    if (!in_array(FALSE, $master, true) && !in_array(FALSE, $master, false)) {
                        $trans->commit();
                        $count++;
                    } else {
                        $trans->rollback();
                        $message = '';
                        foreach ($errors as $array) {
                            foreach ($array as $errorkey => $value) {
                                $message .= $value[0] . '<br>';
                            }
                        }
                        return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                    }

                    $importedPks[] = $model->code;
                } catch (\Exception $e) {
                    $trans->rollback();
                    return ['total' => 0, 'status' => 'error', 'msg' => $e->getMessage(), 'pk' => 0];
                }
            }
        }

        if ($count == count($data) - 1) {
            return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . count($importedPks) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)];
        }

        return ['total' => count($importedPks), 'status' => 'success', 'msg' => 'Among ' . (count($data) - 1) . ' records,' . count($importedPks) . ' records have been processed.', 'pk' => count($importedPks)];
    }

}
