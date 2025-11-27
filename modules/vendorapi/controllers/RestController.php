<?php

namespace app\modules\vendorapi\controllers;

use yii\rest\ActiveController;
use yii\helpers\Json;
use Yii;
use app\models\GeneralModel;
use app\modules\vendorapi\components\HttpResponse;
use app\modules\vendorapi\components\HttpRequest;
use app\modules\vendorapi\Vendorapi;

/**
 * Default controller for the `restservices` module
 */
class RestController extends ActiveController {

    public $response = [
        'success_codes' => [],
        'error_codes' => [],
    ];
    public $post_data = [];
    public $generalModel;
    public $response_master_key = 'master_key';
    public $response_main_array_key = 'Master_Response';
    public $response_inner_array_key = 'Response';
    public $svc;

    public function init() {
        $this->generalModel = new GeneralModel();
    }

    public $modelClass = 'app\modules\vendorapi\models';

    public function actions() {
        return [];
    }

    protected function verbs() {
        return [
            '*' => ['POST'],
        ];
    }

    public function beforeAction($action) {
        parent::beforeAction($action);
        $request = new HttpRequest();
        $this->post_data = $request->ParseRequest();
        $this->response_master_key = $request->response_master_key;
        $this->response_main_array_key = $request->response_main_array_key;
        $this->response_inner_array_key = $request->response_inner_array_key;
        $this->svc = $request->request['svc'];
        if ($this->post_data === FALSE) {
            $this->getError();
        } else {
            return $this->post_data;
        }
    }

    public function afterAction($action, $result) {
        if ($result === FALSE) {
            $this->getError();
        } else {
            $response = new HttpResponse();
            $response->response_master_key = $this->response_master_key;
            $response->response_main_array_key = $this->response_main_array_key;
            $response->response_inner_array_key = $this->response_inner_array_key;
            return $response->BindResponse($this->response);
        }
    }

    public function getError() {
        $type = '';
        $message = '';
        $code = '';
        $master_key = '';
        $error_response = [];
        if (Yii::$app->getSession()->hasFlash('success')) {
            $type = Yii::$app->getSession()->getFlash('success')['type'];
            if ($type == 'success') {
                $code = "200";
            } else {
                $code = "501";
            }
            $message = Yii::$app->getSession()->getFlash('success')['message'];
        }
        $data = Json::decode(Yii::$app->request->getRawBody());
        $svc = $this->svc;
        $master_array = Vendorapi::setParam($svc);
        if (!empty($master_array) && isset($data[$master_array['json_key']])) {
            $save_data = $data[$master_array['json_key']][$master_array['content_json_key']];
            $array = [];
            $array[] = $save_data;
            $convert_array = isset($save_data[0]) ? $save_data : $array;
            $save_data = $convert_array;
            foreach ($save_data as $model_data) {
                $err_resp = [];
                $err_resp['status'] = $code;
                $err_resp[$this->response_master_key] = $model_data[$this->response_master_key];
                $err_resp['desc'] = $message;
                $error_response[] = $err_resp;
            }
        }
        if (empty($error_response)) {
            $err_resp = [];
            $err_resp['status'] = $code;
            $err_resp[$this->response_master_key] = '';
            $err_resp['desc'] = $message;
            $error_response[] = $err_resp;
        }

        $error[$this->response_main_array_key][$this->response_inner_array_key] = $error_response;

        echo json_encode($error);
    }

}
