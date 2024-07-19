<?php

namespace app\components;

use app\modules\feedback\models\TblNonMemberHouseHoldCurrentPouring;
use app\modules\feedback\models\TblNonMemberHouseHoldVisit;
use ruskid\csvimporter\ARImportStrategy;
use Yii;
use yii\base\UserException;

class MemberHouseHoldSurveyImportStrategy extends ARImportStrategy {

    public function import(&$data) {
        $importedPks = [];
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
                    $model = new $this->className;
                    $detailModel = new TblNonMemberHouseHoldCurrentPouring();
                    if (!empty($this->scenario)) {
                        $model->scenario = $this->scenario;
                        $detailModel->scenario = $this->scenario;
                    }
                    foreach ($this->configs as $config) {
                        $value = call_user_func($config['value'], $row);
                        if (isset($config['attribute']) && ($model->hasAttribute($config['attribute']) || $detailModel->hasAttribute($config['attribute']))) {
                            //Set value to the model
                            ($model->hasAttribute($config['attribute'])) ? $model->setAttribute($config['attribute'], $value) : '';
                            ($detailModel->hasAttribute($config['attribute'])) ? $detailModel->setAttribute($config['attribute'], $value) : '';
                        } else if (property_exists($model, $config['attribute'])) {
                            //Set value to the model of public attribute
                            $model->{$config['attribute']} = $value;
                        }
                    }
                    if (empty($model->getErrors()) && empty($detailModel->getErrors()) && $model->validate() && $detailModel->validate()) {
                        $existData = TblNonMemberHouseHoldVisit::find()->where(['visit_date' => $model->visit_date, 'mcc_plant_code' => $model->mcc_plant_code, 'bmc_code' => $model->bmc_code, 'dcs_code' => $model->dcs_code])->one();
                        if(empty($existData)){
                            $model->house_hold_visit_code = "MHHV/" . date("dmYis"). substr((string)microtime(true), 11, 3);
                            $master[] = $model->save();
                            $id = Yii::$app->db->getLastInsertID();
                            $detailModel->house_hold_visit_id = $id;
                        } else {
                            $detailModel->house_hold_visit_id = $existData->house_hold_visit_id;
                        }
                        $master[] = $detailModel->save();
                        if (!in_array(FALSE, $master)) {
                            $trans->commit();
                            $count++;
                            $importedPks[] = $model->primaryKey;
                        } else {
                            $trans->rollback();
                            $message = '';
                            foreach ($model->getErrors() as $value) {
                                $message .= $value[0] . '<br/>';
                            }
                            foreach ($detailModel->getErrors() as $value) {
                                $message .= $value[0] . '<br/>';
                            }
                            return ['total' => 0, 'status' => 'error', 'pk' => 0, 'msg' => 'There is error in Record No : ' . $key . '<br>' . $message];
                        }
                    } else {
                        $message = '';
                        foreach ($model->getErrors() as $value) {
                            $message .= $value[0] . '<br/>';
                        }
                        foreach ($detailModel->getErrors() as $value) {
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
