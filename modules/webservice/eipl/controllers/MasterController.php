<?php

namespace app\modules\webservice\eipl\controllers;

use yii\rest\ActiveController;
use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use app\modules\webservice\components\MyRequest;
use app\modules\webservice\components\MyResponse;
use app\modules\webservice\components\MyResponseCode;
use app\models\GeneralModel;
use app\modules\webservice\components\EiplRequest;
use app\modules\webservice\components\EiplResponse;
use app\modules\webservice\components\EiplResponseCode;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\organisation\models\TblRouteMapping;

/**
 * Default controller for the `restservices` module
 */
class MasterController extends ActiveController {

    public $modelClass = 'app\modules\webservice\models';
    public $layout = false;
    protected $generalModel, $response, $eiplResponseCode;

    public function init() {
        parent::init();
        $this->generalModel = new GeneralModel();
        $this->response = new EiplResponse();
        $this->eiplResponseCode = new EiplResponseCode();
        $this->response->setStatusCode($this->eiplResponseCode->statusSuccess);
    }

    public function actions() {
        return [];
    }

    protected function verbs() {
        return [
            '*' => ['POST'],
        ];
    }

    public function behaviors() {
        return ArrayHelper::merge(
                        parent::behaviors(), [
                    'authenticator' => [
                        'user' => Yii::$app->get('eiplapp'),
                        'class' => CompositeAuth::className(),
                        'except' => ['verify-identity', 'login', 'verify-otp'],
                        'authMethods' => [
                            HttpBearerAuth::className(),
                        ],
                    ],
                        ]
        );
    }

    public function beforeAction($action) {
        parent::beforeAction($action);
        $request = new EiplRequest();
        return $request->ParseRequest();
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
            echo Json::encode($filterErrorArray);
        }
    }

    public function getOrgCodes() {
        $data = [];
        $map_data = [];
        $union = [];
        $plant = [];
        $mcc = [];
        $bmc = [];
        $dcs = [];
        $member = [];
        $loginorg = [];
        if (!empty(Yii::$app->eiplapp->identity->loginOrg)) {
            foreach (Yii::$app->eiplapp->identity->loginOrg as $org) {
                $loginorg['organization_type'] = $org->organization_type;
                $loginorg['organization_code'] = $org->organization_code;
                $map_data[] = $loginorg;
            }
        } else {
            $loginorg['organization_type'] = Yii::$app->eiplapp->identity->login_type;
            $loginorg['organization_code'] = Yii::$app->eiplapp->identity->master_code;
            //   $data['organization_code'] = $loginorg['organization_code'];
            $map_data[] = $loginorg;
        }
        foreach ($map_data as $key => $value) {
            $data['organization_code'][] = $value['organization_code'];
            if ($value['organization_type'] == 'UNION') {
                $union[] = $value['organization_code'];
            } else if ($value['organization_type'] == 'BMC') {
                $bmc[] = $value['organization_code'];
            } else if ($value['organization_type'] == 'MCC') {
                $mcc[] = $value['organization_code'];
            } else if ($value['organization_type'] == 'PLANT') {
                $plant[] = $value['organization_code'];
            } else if ($value['organization_type'] == 'DCS') {
                $dcs[] = $value['organization_code'];
            } else if ($value['organization_type'] == 'MEMBER') {
                $member[] = $value['organization_code'];
            } else if ($value['organization_type'] == 'ROUTE') {
                $model = new TblRouteMapping();
                $model->route_code = $value['organization_code'];
                $model->from_type = 'society';
                foreach ($model->tblRouteMappingSources as $detail) {
                    $dcs[] = $detail->from_dest;
                }
            }
        }
        $data['union'] = $union;
        $data['bmc'] = $bmc;
        $data['dcs'] = $dcs;
        $data['mcc'] = $mcc;
        $data['plant'] = $plant;
        $data['member'] = $member;
        $data['organization_type'] = $loginorg['organization_type'];
        $data['device_id'] = Yii::$app->eiplapp->identity->device_id;
        $data['department'] = Yii::$app->eiplapp->identity->department;
        $data['login_type'] = Yii::$app->eiplapp->identity->login_type;
        return $data;
    }

}
