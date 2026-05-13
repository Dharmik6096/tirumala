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
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\tankermovement\models\TblVehicleTrip;

/**
 * Default controller for the `restservices` module
 */
class MasterController extends ActiveController {

    public $modelClass = 'app\modules\webservice\models';
    public $layout = false;
    protected $generalModel, $eiplResponseCode;

    public function init() {
        parent::init();
        $this->response = [
            'status' => '',
            'message' => [],
            'data' => '',
        ];
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
            return Json::encode($filterErrorArray);
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
        $driver = [];
        $loginorg = [];
        if (strtolower(Yii::$app->eiplapp->identity->login_type) == 'member' || empty(Yii::$app->eiplapp->identity->loginOrg)) {
            $loginorg['organization_type'] = Yii::$app->eiplapp->identity->login_type;
            $loginorg['organization_code'] = Yii::$app->eiplapp->identity->master_code;
            //   $data['organization_code'] = $loginorg['organization_code'];
            $map_data[] = $loginorg;
        } else {
            foreach (Yii::$app->eiplapp->identity->loginOrg as $org) {
                $loginorg['organization_type'] = $org->organization_type;
                $loginorg['organization_code'] = $org->organization_code;
                $map_data[] = $loginorg;
            }
        }
        foreach ($map_data as $key => $value) {
            $data['organization_code'][] = $value['organization_code'];
            if ($value['organization_type'] == 'UNION') {
                $union[] = $value['organization_code'];
            } else if ($value['organization_type'] == 'BMC') {
                $bmc[] = $value['organization_code'];
                $this->setBmcUpperData($union, $plant, $mcc, $value['organization_code']);
            } else if ($value['organization_type'] == 'MCC') {
                $mcc[] = $value['organization_code'];
                $this->setMccUpperData($union, $plant, $value['organization_code']);
            } else if ($value['organization_type'] == 'PLANT') {
                $plant[] = $value['organization_code'];
                $this->setPlantUpperData($union, $value['organization_code']);
            } else if ($value['organization_type'] == 'DCS') {
                $dcs[] = $value['organization_code'];
                $this->setDcsUpperData($union, $plant, $mcc, $bmc, $value['organization_code']);
            } else if ($value['organization_type'] == 'MEMBER') {
                $member[] = $value['organization_code'];
                $model = new TblMember();
                $modelData = $model->findOne($value['organization_code']);
                if (!empty($modelData->dcs_code) && !in_array($modelData->dcs_code, $dcs)) {
                    $dcs[] = $modelData->dcs_code;
                    $this->setDcsUpperData($union, $plant, $mcc, $bmc, $modelData->dcs_code);
                }
            } else if ($value['organization_type'] == 'ROUTE') {
                $model = new TblRouteMapping();
                $model->route_code = $value['organization_code'];
                $model->from_type = 'society';
                foreach ($model->tblRouteMappingSources as $detail) {
                    $dcs[] = $detail->from_dest;
                    $this->setDcsUpperData($union, $plant, $mcc, $bmc, $detail->from_dest);
                }
            } else if ($value['organization_type'] == 'DRIVER') {
                $driver[] = $value['organization_code'];
                $model = TblVehicleTrip::find()->where(['trip_code' => $value['organization_code']])->one();
                if (!empty($model->union_code) && !in_array($model->union_code, $union)) {
                    $union[] = $model->union_code;
                }
            }
        }
        $data['union'] = $union;
        $data['bmc'] = $bmc;
        $data['dcs'] = $dcs;
        $data['mcc'] = $mcc;
        $data['plant'] = $plant;
        $data['member'] = $member;
        $data['driver'] = $driver;
        $data['organization_type'] = $loginorg['organization_type'];
        $data['device_id'] = Yii::$app->eiplapp->identity->device_id;
        $data['department'] = Yii::$app->eiplapp->identity->department;
        $data['login_type'] = Yii::$app->eiplapp->identity->login_type;
        $data['access_token'] = Yii::$app->eiplapp->identity->access_token;
        $data['mobile_no'] = Yii::$app->eiplapp->identity->mobile_no;
        return $data;
    }

    public function setDcsUpperData(&$union, &$plant, &$mcc, &$bmc, $dcs) {
        $model = new TblDcs();
        $modelData = $model->findOne($dcs);
        if (!empty($modelData->union_code) && !in_array($modelData->union_code, $union)) {
            $union[] = $modelData->union_code;
        }
        if (!empty($modelData->plant_code) && !in_array($modelData->plant_code, $plant)) {
            $plant[] = $modelData->plant_code;
        }
        if (!empty($modelData->mcc_plant_code) && !in_array($modelData->mcc_plant_code, $mcc)) {
            $mcc[] = $modelData->mcc_plant_code;
        }
        if (!empty($modelData->bmc_code) && !in_array($modelData->bmc_code, $bmc)) {
            $bmc[] = $modelData->bmc_code;
        }
    }

    public function setBmcUpperData(&$union, &$plant, &$mcc, $bmc) {
        $model = new TblDcsBmc();
        $modelData = $model->findOne($bmc);
        if (!empty($modelData->union_code) && !in_array($modelData->union_code, $union)) {
            $union[] = $modelData->union_code;
        }
        if (!empty($modelData->plant_code) && !in_array($modelData->plant_code, $plant)) {
            $plant[] = $modelData->plant_code;
        }
        if (!empty($modelData->mcc_plant_code) && !in_array($modelData->mcc_plant_code, $mcc)) {
            $mcc[] = $modelData->mcc_plant_code;
        }
    }

    public function setMccUpperData(&$union, &$plant, $mcc) {
        $model = new TblMccPlant();
        $modelData = $model->findOne($mcc);
        if (!empty($modelData->union_code) && !in_array($modelData->union_code, $union)) {
            $union[] = $modelData->union_code;
        }
        if (!empty($modelData->plant_code) && !in_array($modelData->plant_code, $plant)) {
            $plant[] = $modelData->plant_code;
        }
    }

    public function setPlantUpperData(&$union, $plant) {
        $model = new TblPlant();
        $modelData = $model->findOne($plant);
        if (!empty($modelData->union_code) && !in_array($modelData->union_code, $union)) {
            $union[] = $modelData->union_code;
        }
    }

}
