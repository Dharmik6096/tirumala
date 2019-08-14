<?php

namespace app\modules\webservice\eipl\v1\controllers;

use app\modules\webservice\eipl\controllers\MasterController;
use Yii;
use app\modules\webservice\eipl\v1\V1;
use app\modules\webservice\components\EiplRequest;
use yii\helpers\Json;

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
        $message = 'Unable to save!';
        $success_id = [];
        $error_id = [];
        $transaction_data = Yii::$app->request->getRawBody();
        if (!empty($transaction_data['module_name'])) {
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
            $childModel = [];
            if (isset($transaction_data['operation_type']) && strtolower($transaction_data['operation_type']) == 'update') {
                $primaryKey = $model->tableSchema->primaryKey[0];
                $key = $model->$primaryKey;
                $model_data = $model->findOne($key);
                if (!empty($model_data)) {
                    $model = $model_data;
                    $history = $model_name . 'History';
                    $historyModel = new $history();
                    Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                    $childModel[] = $historyModel;
                    $model->setAttributes($saveData);
                }
            }
            if (isset($moduleDetails['save_child']) && $moduleDetails['save_child']) {
                $model->setChildTable($model, $transaction_data, $childModel);
            }

            $transaction = $this->generalModel->saveTransaction([$model], $childModel, ['transactional data', 'create']);
            if ($transaction == 'customRedirect') {
                $message = 'Successfully Saved!';
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

}
