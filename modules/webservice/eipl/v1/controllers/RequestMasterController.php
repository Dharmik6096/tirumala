<?php

namespace app\modules\webservice\eipl\v1\controllers;

use app\modules\webservice\eipl\controllers\MasterController;
use Yii;
use app\modules\webservice\eipl\v1\V1;
use app\modules\webservice\components\EiplRequest;
use yii\helpers\Json;
use app\modules\webservice\eipl\models\TblDpuCollectionHoData;

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
            foreach ($param as $value) {
                $array_val = explode(':', $value);
                $param_val = !empty($array_val[1]) ? $array_val[1] : (isset($req_data[$value]) ? $req_data[$value] : NULL);
                $param_val = empty($param_val) && isset($org_codes[$value]) ? (!empty($org_codes[$value]) ? (is_array($org_codes[$value]) ? (',' . implode(',', $org_codes[$value]) . ',') : $org_codes[$value]) : '0' ) : ((is_array($param_val) ? (',' . implode(',', $param_val) . ',') : $param_val));
                $sp_param[] = $param_val;
            }
            $response = \Yii::$app->general->getSpData($sp_name, $sp_param);
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
            if (isset($moduleDetails['save_child']) && $moduleDetails['save_child']) {
                $model->setChildTable($model, $transaction_data, $childModel);
                $saveModel = true;
            }
            if ($saveModel) {
                $master = [];
                $master[] = $model;
            }
            $transaction = $this->generalModel->saveDeleteTransaction($master, $childModel, $deleteModel, ['Member Family Detail', 'create']);
//            $transaction = $this->generalModel->saveTransaction([$model], $childModel, ['transactional data', 'create']);
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

}
