<?php

namespace app\modules\webservice\eipl\v1\controllers;

use app\modules\webservice\eipl\controllers\MasterController;
use Yii;
use app\modules\webservice\eipl\v1\V1;
use app\modules\webservice\components\EiplRequest;
use yii\helpers\Json;
use app\modules\webservice\eipl\models\TblDpuCollectionHoData;
use app\modules\configuration\models\TblUnionConfigResult;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcsMilkType;
use app\modules\globalmaster\models\TblAnimalType;

class RequestMasterController extends MasterController {

    public function actionIndex() {
        $req_data = Yii::$app->request->getRawBody();
        $endpoint = !empty($req_data['endpoint']) ? $req_data['endpoint'] : NULL;
        $data = V1::getLabels($endpoint);
        $response = [];
        if (!empty($data) && !empty($data['sp']) && (!isset($data['call_action']) || !$data['call_action'])) {
            $sp_name = $data['sp'];
            $sp_param = [];
            $param = !empty($data['param']) ? explode('#', $data['param']) : [];
            $org_codes = $this->getOrgCodes();
            $orgToZero = false;
            if (isset($data['blank_org_to_zero']) && $data['blank_org_to_zero'] == true) {
                $orgToZero = true;
            }
            $rlsArray = !empty($data['rls_param_array']) ? $data['rls_param_array'] : [];

            foreach ($param as $value) {
                $array_val = explode(':', $value);
                $param_val = !empty($array_val[1]) ? $array_val[1] : (isset($req_data[$value]) ? $req_data[$value] : NULL);
                $param_val = empty($param_val) && isset($org_codes[$value]) ? (!empty($org_codes[$value]) && (!$orgToZero || in_array($value, $rlsArray)) ? (is_array($org_codes[$value]) ? (',' . implode(',', $org_codes[$value]) . ',') : $org_codes[$value]) : '0' ) : ((is_array($param_val) ? (',' . implode(',', $param_val) . ',') : $param_val));
                $sp_param[] = $param_val;
            }
            $response = \Yii::$app->general->getSpData($sp_name, $sp_param);
            if (!empty($response)) {
                $dataToDecrypt = !empty($data['to_decrypt']) ? $data['to_decrypt'] : [];
                if (!empty($dataToDecrypt)) {
                    for ($i = 0; $i < count($response); $i++) {
                        foreach ($dataToDecrypt as $decKey) {
                            if (!empty($response[$i]) && !empty($response[$i][$decKey])) {
                                $response[$i][$decKey] = Yii::$app->general->decryptData($response[$i][$decKey]) !== FALSE ? Yii::$app->general->decryptData($response[$i][$decKey]) : $response[$i][$decKey];
                            }
                        }
                    }
                }
            }
            if (isset($data['as_object']) && $data['as_object']) {
                $response = !empty($response) ? $response[0] : NULL;
            }
        } else {
            $endpoint_array = explode('/', $endpoint);
            $cntrlaction = 'webservice/eipl/v1/' . $endpoint_array[0] . '/' . $endpoint_array[1];
            \Yii::$app->request->setRawBody(Json::encode($req_data));
            return Yii::$app->runAction($cntrlaction);
        }
        $this->response->setData($response);
        return $this->response;
    }

    public function actionSaveJson() {
        $data = [];
        $message = Yii::t('app', 'Unable to save!');
        $success_id = [];
        $error_id = [];
        $transaction_data = Yii::$app->request->getRawBody();
        if (!empty($transaction_data['module_name'])) {
            $master = [];
            $endpoint = $transaction_data['module_name'];
            $moduleDetails = V1::getLabels($endpoint);
            $model_name = $moduleDetails['main_table'];
            $model_name = Yii::$app->path->define($model_name);
            $saveData = $transaction_data['content'];
            if (isset($moduleDetails['replace_array_key']) && !empty($moduleDetails['replace_array_key'])) {
                foreach ($moduleDetails['replace_array_key'] as $key => $value) {
                    if (isset($saveData[$key])) {
                        $saveData[$value] = $saveData[$key];
                        unset($saveData[$key]);
                    }
                }
            }
            $model = new $model_name();
            $model->setAttributes($saveData);
            $saveModel = true;
            $childModel = [];
            $deleteModel = [];
            $auto_key_config = [];
            $message = Yii::t('app', 'Successfully Saved!');
            if (isset($transaction_data['operation_type']) && in_array(strtolower($transaction_data['operation_type']), ['update', 'delete'])) {
                $opType = strtoupper($transaction_data['operation_type']);
                $primaryKey = $model->tableSchema->primaryKey[0];
                $key = !empty($model->$primaryKey) ? $model->$primaryKey : (!empty($saveData[$primaryKey]) ? $saveData[$primaryKey] : '');
                $model_data = $model->findOne($key);
                if (!empty($model_data)) {
                    $model = $model_data;
                    $history = $model_name . 'History';
                    $historyModel = new $history();
                    Yii::$app->operation->history($model, $historyModel, $opType);
                    $childModel[] = $historyModel;
                    if ($opType == 'DELETE') {
                        $deleteModel[] = $model;
                        $message = Yii::t('app', 'Successfully deleted!');
                    } else {
                        $model->setAttributes($saveData);
                    }
                }
                $saveModel = $opType == 'DELETE' ? false : true;
            }
            if (isset($moduleDetails['multi_auto_increment_key']) && $moduleDetails['multi_auto_increment_key']) {
                $model->setChildTable($model, $transaction_data, $childModel, $auto_key_config);
                $saveModel = true;
            } else if (isset($moduleDetails['save_child']) && $moduleDetails['save_child']) {
                $model->setChildTable($model, $transaction_data, $childModel);
                $saveModel = true;
            }

            if (isset($moduleDetails['multi_auto_inc_key_save_other']) && $moduleDetails['multi_auto_inc_key_save_other']) {
                $model->setChildTableOther($model, $transaction_data, $childModel, $auto_key_config);
                $saveModel = true;
            } else if (isset($moduleDetails['save_child_other']) && $moduleDetails['save_child_other']) {
                $model->setChildTableOther($model, $transaction_data, $childModel);
                $saveModel = true;
            }
            if ($saveModel && !isset($moduleDetails['multi_auto_increment_key']) && !isset($moduleDetails['multi_auto_inc_key_save_other'])) {
                $master = [];
                $master[] = $model;
            }
            if (!empty($auto_key_config)) {
                $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($childModel, ['transactional data', 'create'], $auto_key_config);
            } else {
                $transaction = $this->generalModel->saveDeleteTransaction($master, $childModel, $deleteModel, ['Member Family Detail', 'create']);
//            $transaction = $this->generalModel->saveTransaction([$model], $childModel, ['transactional data', 'create']);
            }
            if ($transaction == 'customRedirect') {
                $message = $message;
            } else {
                $message = Yii::t('app', 'Unable to save!');
            }
        }
        $this->response->setMessage([$message]);
        return $this->response;
    }

    public function SetDataType($model) {
        $scema = $model->getTableSchema();
        foreach ($model->attributes as $key => $a) {
            $type = $scema->columns[$key]->type;
            if ($type == 'boolean') {
                $a = ($a == 1) ? true : false;
            } elseif ($type == 'smallint') {
                $a = (int) $a;
            } elseif ($type == 'decimal') {
                $a = (double) $a;
            } elseif ($type == 'bigint') {
                $a = (int) $a;
            }
            $model->{$key} = $a;
        }
        return $model;
    }

    public function actionSaveDpuCollectionData() {
        $records = Yii::$app->request->getRawBody();
        $successId = [];
        $errorId = [];
        $setData = [];
        $currentDateTime = date('Y-m-d H:i:s');
        $identityRecord = !empty(Yii::$app->eiplapp->identity) ? Yii::$app->eiplapp->identity : [];
        $setData['access_token'] = !empty($identityRecord['access_token']) ? $identityRecord['access_token'] : NULL;
        $setData['identity_type'] = !empty($identityRecord['login_type']) ? $identityRecord['login_type'] : NULL;
        $setData['mobile_no'] = !empty($identityRecord['mobile_no']) ? $identityRecord['mobile_no'] : NULL;
        $setData['device_id'] = !empty($identityRecord['device_id']) ? $identityRecord['device_id'] : NULL;
        $setData['entry_type'] = 'HTTP';
        $setData['status'] = 0;
        $setData['entry_datetime'] = $currentDateTime;
        foreach ($records as $record) {
            $model = new TblDpuCollectionHoData();
            $model->setAttributes($record);
            $model->setAttributes($setData);
            if ($model->save()) {
                $successId[] = $model->uuid;
            } else {
                $errorId[] = $model->uuid;
            }
        }
        $response = [];
        $response['success_id'] = $successId;
        $response['error_id'] = $errorId;
        $this->response->setData($response);
        $message = 'Successfully Saved!';
        $this->response->setMessage([$message]);
        return $this->response;
    }

    public function actionStartUp() {
        $res_data = [];
        $data = Yii::$app->request->getRawBody();
        if (!empty($data['organization_code']) && !empty($data['organization_type'])) {
            $res_data['config'] = [];
            $org_code = $data['organization_code'];
            $org_type = $data['organization_type'];
            $orgDetail = $this->getOrgDetail($org_type, $org_code, FALSE);
            $model_data = $orgDetail['model_data'];
            if (!empty($model_data)) {
                $animalType = [];
                $min_fat = $min_snf = $min_clr = $max_fat = $max_snf = $max_clr = 0.0;
                $milktype = new TblDcsMilkType();
                $rate_chart_range = $milktype->rateChart($model_data->union_code, $org_type);
                foreach ($rate_chart_range as $rate_chart) {
                    if (!empty($rate_chart)) {
                        $min_fat = $rate_chart->min_fat;
                        $max_fat = $rate_chart->max_fat;
                        $min_snf = $rate_chart->min_snf;
                        $max_snf = $rate_chart->max_snf;
                        $min_clr = $rate_chart->min_clr;
                        $max_clr = $rate_chart->max_clr;
                        $milk_type_data = TblAnimalType::find()->select(['animal_type_code', 'animal_type_name'])->where(['animal_type_code' => $rate_chart->animal_type_code])->one();
                    }
                    $animalType[] = [
                        'milk_type_code' => !empty($milk_type_data->animal_type_code) ? $milk_type_data->animal_type_code : '',
                        'milk_type_name' => !empty($milk_type_data->animal_type_name) ? $milk_type_data->animal_type_name : '',
                        'min_fat' => $min_fat,
                        'max_fat' => $max_fat,
                        'min_snf' => $min_snf,
                        'max_snf' => $max_snf,
                        'min_clr' => $min_clr,
                        'max_clr' => $max_clr
                    ];
                }
                $res_data['collectionConfig']['allowedMilkType'] = $animalType;
            }
            $model = new TblUnionConfigResult();
            $model->union_code = $model_data->union_code;
            $model->config_for = $org_type;
            foreach ($model->getConfigList() as $d) {
                $res_data['config'][$d['config_key']] = $d['config_result_key'];
            }
        }
        $this->response->setData($res_data);
        return $this->response;
    }

    public function getOrgDetail($type, $code, $is_string = TRUE) {
        $dcs_code = [];
        $bmc_code = [];
        $mcc_plant_code = [];
        $plant_code = [];
        $union_code = '';
        $model_data = [];
        if ($type == 'VLC') {
            $model = new TblDcs();
            $model->dcs_code = $code;
            $dcs_code[] = $code;
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $union_code = $model_data->union_code;
                $bmc_code[] = $model_data->bmc_code;
                $mcc_plant_code[] = $model_data->mcc_plant_code;
                $plant_code[] = $model_data->plant_code;
            }
        } else if ($type == 'BMC') {
            $model = new TblDcsBmc();
            $model->bmc_code = $code;
            $model_data = $model->singleBmcData();
            if (!empty($model_data)) {
                $union_code = $model_data->union_code;
                $plant_code = ArrayHelper::getColumn($model_data->unionCode->tblPlant, 'plant_code');
                $mcc_plant_code = ArrayHelper::getColumn($model_data->tblMccPlant->tblMccPlantGroup, 'p_mcc_plant_code');
                $mcc_plant_code[] = $model_data->mcc_plant_code;
                $bmc_code = ArrayHelper::getColumn($model_data->tblBmcGroup, 'p_bmc_code');
                $bmc_code[] = $model_data->bmc_code;
                $dcs_code = ArrayHelper::getColumn($model_data->dcsCodes, 'dcs_code');
                foreach ($model_data->tblBmcGroup as $bmc) {
                    $dcs_code = array_merge($dcs_code, ArrayHelper::getColumn($bmc->tblDcsCode, 'dcs_code'));
                }
            }
        } else if ($type == 'MCC') {
            $model = new TblMccPlant();
            $model->mcc_plant_code = $code;
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $union_code = $model_data->union_code;
                $plant_code = ArrayHelper::getColumn($model_data->unionCode->tblPlant, 'plant_code');
                $mcc_plant_code = ArrayHelper::getColumn($model_data->tblMccPlantGroup, 'p_mcc_plant_code');
                $mcc_plant_code[] = $model_data->mcc_plant_code;
                $bmc_code = ArrayHelper::getColumn($model_data->bmcCodes, 'bmc_code');
                $dcs_code = ArrayHelper::getColumn($model_data->tblDcs, 'dcs_code');
                foreach ($model_data->tblMccPlantGroup as $mcc) {
                    $bmc_code = array_merge($bmc_code, ArrayHelper::getColumn($mcc->tblBmcCode, 'bmc_code'));
                    $dcs_code = array_merge($dcs_code, ArrayHelper::getColumn($mcc->tblDcsCode, 'dcs_code'));
                }
            }
        } else if ($type == 'ROUTE') {
            $model = new TblDcs();
            $model->route_code = $code;
            $model_data = $model->getRouteDcs($code);
            $dcs_code = ArrayHelper::getColumn($model_data, 'dcs_code');
        }
        if ($is_string) {
            $dcs_code = implode('\',\'', $dcs_code);
            $bmc_code = implode('\',\'', $bmc_code);
            $mcc_plant_code = implode('\',\'', $mcc_plant_code);
            $plant_code = implode('\',\'', $plant_code);
            $dcs_code = !empty($dcs_code) ? '\'' . $dcs_code . '\'' : $dcs_code;
            $bmc_code = !empty($bmc_code) ? '\'' . $bmc_code . '\'' : $bmc_code;
            $mcc_plant_code = !empty($mcc_plant_code) ? '\'' . $mcc_plant_code . '\'' : $mcc_plant_code;
            $plant_code = !empty($plant_code) ? '\'' . $plant_code . '\'' : $plant_code;
        }
        return ['dcs_code' => $dcs_code, 'bmc_code' => $bmc_code, 'mcc_plant_code' => $mcc_plant_code, 'plant_code' => $plant_code, 'union_code' => $union_code, 'model_data' => $model_data];
    }

}
