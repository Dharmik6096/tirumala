<?php

namespace app\modules\embededdpu\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\embededdpu\components\HttpRequest;
use app\modules\embededdpu\components\HttpResponse;
use app\models\GeneralModel;

/**
 * Default controller for the `restservices` module
 */
class RestController extends ActiveController {

//    public $response = [
//        'status' => '',
//        'message' => [],
//        'data' => '',
//    ];
    public $modelClass = 'app\modules\embededdpu\models';
    public $post_data = [];
    public $apply_camel_case = TRUE;
    protected $generalModel;

//    public $generalModel = new GeneralModel();


    public function init() {
        parent::init();
        $this->generalModel = new GeneralModel();
        $this->response = [
            'status' => '',
            'message' => [],
            'data' => '',
        ];
    }

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
        return $request->ParseRequest();
    }

    public function afterAction($action, $result) {
        if ($result === FALSE) {
            $this->getError();
        } else {
            //$result = parent::afterAction($action, $result);
            $response = new HttpResponse();
            $response->apply_camel_case = $this->apply_camel_case;
            return $response->BindResponse($this->response);
        }
    }

    public function getError() {
        $type = '';
        $message = [];
        if (Yii::$app->getSession()->hasFlash('success')) {
            $type = Yii::$app->getSession()->getFlash('success')['type'];
            if (!is_array(Yii::$app->getSession()->getFlash('success')['message'])) {
                $message = [Yii::$app->getSession()->getFlash('success')['message']];
            } else {
                $message = Yii::$app->getSession()->getFlash('success')['message'];
            }
        }
        $error = [
            'status' => $type,
            'error' => ['code' => '', 'message' => $message],
            'data' => [],
        ];
        echo json_encode($error);
    }

}
