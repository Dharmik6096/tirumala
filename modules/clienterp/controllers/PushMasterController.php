<?php

namespace app\modules\clienterp\controllers;

use yii\rest\ActiveController;
use Yii;
use yii\helpers\Json;
use app\modules\clienterp\components\EiplRequest;
use app\modules\clienterp\components\EiplResponse;
use app\modules\clienterp\components\EiplResponseCode;
use app\models\GeneralModel;

class PushMasterController extends ActiveController {

    public $modelClass = 'app\modules\clienterp\models';
    public $layout = false;
    protected $eiplResponseCode;
    protected $generalModel;

    public function init() {
        parent::init();
        $this->response = new EiplResponse();
        $this->eiplResponseCode = new EiplResponseCode();
        $this->response->setStatusCode($this->eiplResponseCode->statusSuccess);
        $this->generalModel = new GeneralModel();
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
        $request = new EiplRequest();
        $request = $request->ParseRequest();
        if ($request === FALSE) {
            $type = Yii::$app->getSession()->getFlash('success')['type'];
            $message = [Yii::$app->getSession()->getFlash('success')['message']];
            $this->response->setStatusCode(!empty($this->eiplResponseCode->{$type}) ? $this->eiplResponseCode->{$type} : $this->eiplResponseCode->statusError);
            $this->response->setMessage($message);
            exit(json_encode($this->response));
        } else {
            return $request;
        }
    }

}
