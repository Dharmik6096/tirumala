<?php

namespace app\modules\webservice\emilkprolite\controllers;

use yii\rest\ActiveController;
use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\models\GeneralModel;
use app\modules\webservice\components\EmilkProLiteRequest;
use app\modules\webservice\components\EmilkProLiteResponse;
use app\modules\webservice\components\EiplResponse;
use app\modules\webservice\components\EiplResponseCode;

/**
 * Default controller for the `restservices` module
 */
class MasterController extends ActiveController {

    public $modelClass = 'app\modules\webservice\models';
    public $layout = false;
    protected $generalModel, $eiplResponseCode;
//    public $response = [
//        'status' => '',
//        'message' => [],
//        'data' => '',
//    ];
    public $post_data = [];
    public $apply_camel_case = true;

    public function init() {
        parent::init();
        $this->generalModel = new GeneralModel();
        $this->response = [
            'status' => '',
            'message' => [],
            'data' => '',
        ];
//        $this->response = new EiplResponse();
//        $this->eiplResponseCode = new EiplResponseCode();
//        $this->response->setStatusCode($this->eiplResponseCode->statusSuccess);
    }

    public function actions() {
        return [];
    }

    protected function verbs() {
        return [
            '*' => ['POST'],
        ];
    }

//    public function behaviors() {
//        return ArrayHelper::merge(
//                        parent::behaviors(), [
//                    'authenticator' => [
//                        'user' => Yii::$app->get('emilkProLiteApp'),
//                        'class' => CompositeAuth::className(),
//                        'except' => ['verify-identity', 'login', 'verify-otp'],
//                        'authMethods' => [
//                            HttpBearerAuth::className(),
//                        ],
//                    ],
//                        ]
//        );
//    }

    public function beforeAction($action) {
        parent::beforeAction($action);
        $request = new EmilkProLiteRequest();
        $this->post_data = $request->ParseRequest();
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
            //$result = parent::afterAction($action, $result);
            $response = new EmilkProLiteResponse();
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

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::$app->errorHandler->error) {
            $filterErrorArray = array(
                'code' => $error['code'],
                'errorCode' => $error['errorCode'],
                'message' => $error['message'],
            );
            if (Yii::$app->params['apidebug']) {
                $filterErrorArray['type'] = $error['type'];
                $filterErrorArray['file'] = $error['file'];
                $filterErrorArray['line'] = $error['line'];
            }
            return Json::encode($filterErrorArray);
        }
    }

}
