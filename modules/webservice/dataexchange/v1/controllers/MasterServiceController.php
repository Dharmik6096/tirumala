<?php

namespace app\modules\webservice\dataexchange\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\helpers\Json;
use app\modules\vendorapi\models\TblDataExchangeActivation;
use app\modules\webservice\dataexchange\v1\V1;
use app\modules\vendorapi\models\TblVendorApiData;
use app\modules\vendorapi\models\TblVendorApiRequestLog;

class MasterServiceController extends ActiveController {

    public $modelClass = 'app\modules\webservice\models';

    public function actions() {
        return [];
    }

    protected function verbs() {
        return [
            '*' => ['POST'],
        ];
    }

    public function actionCreateDcs() {
        $message = 'Validation Fails';
        $message1 = 'Token Is Invalid';
        $response = [
            'code' => '400',
            'status' => 'ERROR',
            'message' => $message,
            'data' => ['message' => $message1],
        ];
        $post_data = Json::decode(Yii::$app->request->getRawBody());
        $header = getallheaders();
        $token = '';
        if (!empty($header)) {
            if (!empty($header['token'])) {
                $token = $header['token'];
            } else if (!empty($header['authorization'])) {
                $token = $header['authorization'];
            }
        }

        if ($token) {
            $date = date('Y-m-d H:i:s');
            $activatemodel = new TblDataExchangeActivation();
            $data = $activatemodel->find()
                    ->where(['token' => $token])
                    ->andWhere('((\'' . $date . '\' between valid_from  and valid_to))')
                    ->one();
            if (!empty($data)) {
                $save = $this->manipulation($post_data, 'dcs_create', $data);
                if ($save) {
                    $response = [
                        'code' => '200',
                        'status' => 'SUCCESS',
                        'message' => 'Operation performed successfully',
                    ];
                } else {
                    $message = 'Validation Fails';
                    $message1 = 'filed validation fails';
                    $response = [
                        'code' => '400',
                        'status' => 'ERROR',
                        'message' => $message,
                        'data' => ['message' => $message1],
                    ];
                }
            }
        }
//        $data = json_encode($response);
//        echo $data;
        $data = $response;
        return $data;
    }

    public function manipulation($model_data, $arrayKey = 'dcs_create', $apiData) {
        $master_array = V1::setParam($arrayKey);
        $master_model = [];
        $valid = [];
        $array = [];
        $array['server'] = $_SERVER;
        $header = getallheaders();
        $array['header'] = $header;
        $array = json_encode($array);

        $log_model = new TblVendorApiRequestLog();
        $log_model->url = Yii::$app->request->absoluteUrl;
        $log_model->request = Yii::$app->request->getRawBody();
        $log_model->request_original = $array;
        $log_model->request_ip = $_SERVER['REMOTE_ADDR'];
        $log_model->status = true;
        $log_model->created_at = date('Y-m-d H:i:s');
        $log_model->save(false);


        $model = new TblVendorApiData();
        if (isset($master_array['scenario'])) {
            $model->scenario = $arrayKey;
        }
        foreach ($master_array as $key => $value) {
            $param = explode(':', $value);
            if ($key != $param[0]) {
                if (!empty($model_data[$key])) {
                    $model_data[$key] = substr($model_data[$key], 0, 1) == '"' && substr($model_data[$key], -1, 1) == '"' ? substr($model_data[$key], 1, -1) : $model_data[$key];
                }
                $model_data[$param[0]] = isset($model_data[$key]) ? $model_data[$key] : NULL;
            }
            if (isset($param[1]) && $param[1] == 'date') {
                $model_data[$param[0]] = !empty($model_data[$param[0]]) ? date('Y-m-d', strtotime($model_data[$param[0]])) : '';
            }
        }
        $model->setAttributes($model_data);
        $model->username = \Yii::$app->params['data_exchange_un'];
        $model->password = \Yii::$app->params['data_exchange_pw'];
        $model->service_type = $arrayKey;
        $model->type_of_data = 'JSON';
        $model->union_code = '001'; //001
        $model->log_id = $log_model->log_id;
        $model->mobile_no = (string) $model->mobile_no;
        $model->parent_code = (string) $model->parent_code;
        $model->created_at = date('Y-m-d H:i:s');
        $model->created_by = '001';
        $model->master_code = !empty($model->master_code) ? (string) $model->master_code : NULL;
        $model->bank_account_no = !empty($model->bank_account_no) ? (string) $model->bank_account_no : NULL;

        if ($model->validate() && $model->save(false)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function actionRouteCreate() {
        $message = 'Validation Fails';
        $message1 = 'Token Is Invalid';
        $response = [
            'code' => '400',
            'status' => 'ERROR',
            'message' => $message,
            'data' => ['message' => $message1],
        ];
        $post_data = Json::decode(Yii::$app->request->getRawBody());
        $header = getallheaders();
        if (!empty($header) && !empty($header['token'])) {
            $date = date('Y-m-d H:i:s');
            $activatemodel = new TblDataExchangeActivation();
            $data = $activatemodel->find()
                    ->where(['token' => $header['token']])
                    ->andWhere('((\'' . $date . '\' between valid_from  and valid_to))')
                    ->one();
            if (!empty($data)) {
                $save = $this->manipulation($post_data, 'route_create', $data);
                if ($save) {
                    $response = [
                        'code' => '200',
                        'status' => 'SUCCESS',
                        'message' => 'Operation performed successfully',
                    ];
                } else {
                    $message = 'Validation Fails';
                    $message1 = 'filed validation fails';
                    $response = [
                        'code' => '400',
                        'status' => 'ERROR',
                        'message' => $message,
                        'data' => ['message' => $message1],
                    ];
                }
            }
        }
//        $data = json_encode($response);
//        echo $data;
        $data = $response;
        return $data;
    }

}
