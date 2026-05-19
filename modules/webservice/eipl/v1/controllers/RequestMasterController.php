<?php

namespace app\modules\webservice\eipl\v1\controllers;

use app\modules\webservice\eipl\controllers\MasterController;
use Yii;
use app\modules\webservice\eipl\v1\V1;
use app\modules\webservice\components\EiplRequest;
use yii\helpers\Json;
use app\modules\webservice\eipl\models\TblDpuCollectionHoData;
use app\modules\configuration\models\TblUnionConfigResult;
use app\modules\configuration\models\TblUnionRatechartRange;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;

class RequestMasterController extends MasterController {

    public function actionIndex() {
        $req_data = Yii::$app->request->getRawBody();
        $endpoint = !empty($req_data['endpoint']) ? $req_data['endpoint'] : NULL;
        $data = V1::getLabels($endpoint);
        $response = [];
        if (!empty($data) && !empty($data['sp']) && (!isset($data['call_action']) || !$data['call_action'])) {
            $is_cache = isset($data['redis']) && $data['redis'] === true;
            $cache_key = '';
            if ($is_cache && Yii::$app->has('redis')) {
                $redis = Yii::$app->get('redis');
                $req_string = is_array($req_data) ? json_encode($req_data) : (string) $req_data;
                $cache_key = 'v1_' . str_replace('/', '_', $endpoint) . '_' . md5($req_string);
                $cached_data = $redis->get($cache_key);
                if ($cached_data !== false && $cached_data !== null) {
                    $response = json_decode($cached_data, true);
                    if (!empty($response)) {
                        $this->response->setData($response);
                        return $this->response;
                    }
                }
            }
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
            if ($is_cache && !empty($response) && !empty($cache_key) && Yii::$app->has('redis')) {
                $redis = Yii::$app->get('redis');
                $redis->setex($cache_key, 86400, json_encode($response));
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
        $is_validate = true;
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
            } else {
                $model->originating_org_type = 'MOBILE';
                $model->originating_type = 0;
                $model->created_by = !empty(Yii::$app->eiplapp->identity['module_code']) ? Yii::$app->eiplapp->identity['module_code'] : '';
            }
            if (isset($moduleDetails['multi_auto_increment_key']) && $moduleDetails['multi_auto_increment_key']) {
                $childModel = [];
                $deleteModel = [];
                $model->setChildTable($model, $transaction_data, $childModel, $auto_key_config);
                $saveModel = true;
            } else if (isset($moduleDetails['save_child']) && $moduleDetails['save_child']) {
                $model->setChildTable($model, $transaction_data, $childModel);
                $saveModel = true;
            }

            if (isset($moduleDetails['multi_auto_inc_key_save_other']) && $moduleDetails['multi_auto_inc_key_save_other']) {
                $childModel = [];
                $deleteModel = [];
                $model->setChildTableOther($model, $transaction_data, $childModel, $auto_key_config);
                $saveModel = true;
            } else if (isset($moduleDetails['save_child_other']) && $moduleDetails['save_child_other']) {
                $model->setChildTableOther($model, $transaction_data, $childModel);
                $saveModel = true;
            }
            if ($saveModel && !isset($moduleDetails['multi_auto_increment_key']) && !isset($moduleDetails['multi_auto_inc_key_save_other'])) {
                $master = [];
                if (isset($model->union_code)) {
                    $model->originating_org_code = $model->union_code;
                }
                $master[] = $model;
            }
            if (!empty($auto_key_config)) {
                $is_validate = false;
                if (empty($childModel[0]->getErrors())) {
                    $is_validate = true;
                    $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($childModel, ['transactional data', 'create'], $auto_key_config);
                }
                $master[] = $childModel[0];
            } else {
                if (!empty($master) && !empty($master[0]->getErrors())) {
                    $is_validate = false;
                } else if (empty($master) && !empty($childModel[0]->getErrors())) {
                    $is_validate = false;
                    $master[] = $childModel[0];
                }
                if ($is_validate) {
                    $transaction = $this->generalModel->saveDeleteTransaction($master, $childModel, $deleteModel, ['Member Family Detail', 'create']);
                }
//            $transaction = $this->generalModel->saveTransaction([$model], $childModel, ['transactional data', 'create']);
            }
            if (!empty($transaction) && $transaction == 'customRedirect') {
                $message = $message;
            } else {
                $is_validate = false;
                $message = '';
                if (!$is_validate) {
                    foreach ($master as $singleModel) {
                        $errors = $singleModel->getErrors();
                        if ($errors) {
                            foreach ($errors as $attribute => $errorMessages) {
                                $message .= implode(' ', $errorMessages) . ' ';
                            }
                        }
                    }
                }
                if (empty($message)) {
                    $message = Yii::t('app', 'Unable to save!');
                }
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
        $orgCodes = $this->getOrgCodes();
        $union = !empty($orgCodes['union'][0]) ? $orgCodes['union'][0] : '';
        $org_type = !empty($orgCodes['organization_type']) ? $orgCodes['organization_type'] : '';
        $org_code = !empty($orgCodes['organization_code']) ? $orgCodes['organization_code'] : '';
        if (!empty($union)) {
            $animalType = [];
            $min_fat = $min_snf = $min_clr = $max_fat = $max_snf = $max_clr = 0.0;
            if (!empty($org_code) && (count($org_code) == 1) && ($org_type == 'DCS' || $org_type == 'BMC')) {
                if ($org_type == 'DCS') {
                    $model_data = new TblDcs();
                    $model_data->dcs_code = $org_code[0];
                    $MappedMilkType = $model_data->tblDcsMilkType;
                } else if ($org_type == 'BMC') {
                    $model_data = new TblDcsBmc();
                    $model_data->bmc_code = $org_code[0];
                    $MappedMilkType = $model_data->tblBmcMilkType;
                }
                foreach ($MappedMilkType as $milktype) {
                    $milktype->app_type = ($org_type == 'DCS') ? 'VLC' : 'BMC';
                    $rate_range = $milktype->rateChartRange;
                    if (!empty($rate_range)) {
                        $min_fat = $rate_range->min_fat;
                        $max_fat = $rate_range->max_fat;
                        $min_snf = $rate_range->min_snf;
                        $max_snf = $rate_range->max_snf;
                        $min_clr = $rate_range->min_clr;
                        $max_clr = $rate_range->max_clr;
                    }
                    $animalType = [
                        'milk_type_code' => $milktype->milk_type_code,
                        'milk_type_name' => $milktype->milkTypeCode->animal_type_name,
                        'min_fat' => $min_fat,
                        'max_fat' => $max_fat,
                        'min_snf' => $min_snf,
                        'max_snf' => $max_snf,
                        'min_clr' => $min_clr,
                        'max_clr' => $max_clr
                    ];
                    $res_data[strtolower($milktype['app_type'])]['collectionConfig']['allowedMilkType'][] = $animalType;
                }
            } else {
                $rateChart = new TblUnionRatechartRange();
                $rate_chart_range = $rateChart->rateChart($union);
                foreach ($rate_chart_range as $rate_chart) {
                    if (!empty($rate_chart)) {
                        $min_fat = $rate_chart['min_fat'];
                        $max_fat = $rate_chart['max_fat'];
                        $min_snf = $rate_chart['min_snf'];
                        $max_snf = $rate_chart['max_snf'];
                        $min_clr = $rate_chart['min_clr'];
                        $max_clr = $rate_chart['max_clr'];
                    }
                    $animalType = [
                        'milk_type_code' => !empty($rate_chart['animal_type_code']) ? $rate_chart['animal_type_code'] : '',
                        'milk_type_name' => !empty($rate_chart['animal_type_name']) ? $rate_chart['animal_type_name'] : '',
                        'min_fat' => $min_fat,
                        'max_fat' => $max_fat,
                        'min_snf' => $min_snf,
                        'max_snf' => $max_snf,
                        'min_clr' => $min_clr,
                        'max_clr' => $max_clr
                    ];
                    $res_data[strtolower($rate_chart['config_for'])]['collectionConfig']['allowedMilkType'][] = $animalType;
                }
            }
            $model = new TblUnionConfigResult();
            $model->union_code = $union;
            $model->config_for = ['VLC', 'BMC'];
            foreach ($model->getConfigList() as $d) {
                $res_data[strtolower($d['config_for'])]['config'][$d['config_key']] = $d['config_result_key'];
            }
        }
        $this->response->setData($res_data);
        return $this->response;
    }

}
