<?php

namespace app\modules\vendorapi\controllers;

use yii\rest\ActiveController;
use yii\helpers\Json;
use Yii;
use app\models\GeneralModel;
use app\modules\vendorapi\components\HttpResponse;
use app\modules\vendorapi\components\HttpRequest;

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
            return $response->BindResponse($this->response);
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
            'response' => ['status' => $code, $this->response_master_key => $master_key, 'desc' => $message],
        ];
        echo json_encode($error);
    }

}
