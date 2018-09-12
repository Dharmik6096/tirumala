<?php

namespace app\modules\vendorapi\controllers;

use yii\rest\ActiveController;
use yii\helpers\Json;
use Yii;
use app\models\GeneralModel;
use app\modules\vendorapi\components\HttpResponse;
use app\modules\vendorapi\components\HttpRequest;
use app\modules\vendorapi\models\TblVendorApiRequestLog;

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
        if ($this->post_data === FALSE) {
            $this->getError();
        } else {
            return $this->post_data;
        }
    }

    public function afterAction($action, $result) {
        $data = '';
        $return = false;
        if ($result === FALSE) {
            $this->getError();
        } else {
            $return = true;
            $response = new HttpResponse();
            $data = $response->BindResponse($this->response);
        }
        $this->saveVendorApiLog($data);
        if ($return) {
            return $data;
        }
    }

    public function getError() {
        $type = '';
        $message = '';
        $code = '';
        $master_key = '';
        if (Yii::$app->getSession()->hasFlash('success')) {
            $type = Yii::$app->getSession()->getFlash('success')['type'];
            if ($type == 'success') {
                $code = 200;
            } else {
                $code = 501;
            }
            $message = Yii::$app->getSession()->getFlash('success')['message'];
        }
        $error = [
            'response' => ['code' => $code, 'master_key' => $master_key, 'message' => $message],
        ];
        echo json_encode($error);
    }

    private function saveVendorApiLog($data) {
        $log_model = new TblVendorApiRequestLog();
        $log_model->union_code = !empty($this->post_data['union_code']) ? $this->post_data['union_code'] : NULL;
        $log_model->url = Yii::$app->request->absoluteUrl;
        $log_model->request = json_encode($this->post_data);
        $log_model->request_original = Yii::$app->request->getRawBody();
        $log_model->request_ip = $_SERVER['REMOTE_ADDR'];
        $log_model->status = true;
        $log_model->response = json_encode($data);
        $log_model->save();
    }

}
