<?php

namespace app\modules\androiddpu\controllers;

use yii\rest\ActiveController;
use Yii;
use app\modules\androiddpu\components\HttpRequest;
use app\modules\androiddpu\components\HttpResponse;
use app\models\GeneralModel;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblPlant;

/**
 * Default controller for the `restservices` module
 */
class RestController extends ActiveController {

//    public $response = [
//        'status' => '',
//        'message' => [],
//        'data' => '',
//    ];
    public $modelClass = 'app\modules\androiddpu\models';
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
        $request = Yii::$app->get('androidHttpRequest');
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
            $response = Yii::$app->get('androidHttpResponse');
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
        die;
    }
    public function getOrgDetail($type, $code, $is_string = TRUE) {
        $dcs_code = [];
        $bmc_code = [];
        $mcc_plant_code = [];
        $plant_code = [];
        $union_code = '';
        $eipl_code = '';
        $model_data = [];
        $applicability_type = 0;

        if ($type == 'VLC') {
            $model = new TblDcs();
            $model->dcs_code = $code;
            $dcs_code[] = $code;
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $union_code = $model_data->union_code;
                $bmc_code[] = $model_data->bmc_code;
                $mcc_plant_code[] = $model_data->mcc_plant_code;
                $plant_code[] = $model_data->plant_code;
                $eipl_code = Yii::$app->general->getforeignkey($model_data->unionCode, 'eipl_code');
            }
            $applicability_type = 2;
        } else if ($type == 'BMC') {
            $model = new TblDcsBmc();
            $model->bmc_code = $code;
            $model_data = $model->singleBmcData();
            if (!empty($model_data)) {
                $union_code = $model_data->union_code;
                $plant_code = ArrayHelper::getColumn($model_data->unionCode->tblPlant, 'plant_code');
                $mcc_plant_code = ArrayHelper::getColumn($model_data->tblMccPlant->tblMccPlantGroup, 'p_mcc_plant_code');
                $mcc_plant_code[] = $model_data->mcc_plant_code;
                $bmc_code = ArrayHelper::getColumn($model_data->tblBmcGroup, 'p_bmc_code');
                $bmc_code[] = $model_data->bmc_code;
                $dcs_code = ArrayHelper::getColumn($model_data->dcsCodes, 'dcs_code');
                foreach ($model_data->tblBmcGroup as $bmc) {
                    $dcs_code = array_merge($dcs_code, ArrayHelper::getColumn($bmc->tblDcsCode, 'dcs_code'));
                }
                $eipl_code = Yii::$app->general->getforeignkey($model_data->unionCode, 'eipl_code');
            }
            $applicability_type = 1;
        } else if ($type == 'MCC') {
            $model = new TblMccPlant();
            $model->mcc_plant_code = $code;
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $union_code = $model_data->union_code;
                $plant_code = ArrayHelper::getColumn($model_data->unionCode->tblPlant, 'plant_code');
                $mcc_plant_code = ArrayHelper::getColumn($model_data->tblMccPlantGroup, 'p_mcc_plant_code');
                $mcc_plant_code[] = $model_data->mcc_plant_code;
                $bmc_code = ArrayHelper::getColumn($model_data->bmcCodes, 'bmc_code');
                $dcs_code = ArrayHelper::getColumn($model_data->tblDcs, 'dcs_code');
                foreach ($model_data->tblMccPlantGroup as $mcc) {
                    $bmc_code = array_merge($bmc_code, ArrayHelper::getColumn($mcc->tblBmcCode, 'bmc_code'));
                    $dcs_code = array_merge($dcs_code, ArrayHelper::getColumn($mcc->tblDcsCode, 'dcs_code'));
                }
                $eipl_code = Yii::$app->general->getforeignkey($model_data->unionCode, 'eipl_code');
            }
            $applicability_type = 1;
        } else if ($type == 'ROUTE') {
            $model = new TblDcs();
            $model->route_code = $code;
            $model_data = $model->getRouteDcs($code);
            $dcs_code = ArrayHelper::getColumn($model_data, 'dcs_code');
        } else if ($type == 'PLANT') {
            $model = new TblPlant();
            $model->plant_code = $code;
            $model_data = $model->getData();
            if (!empty($model_data)) {
                $union_code = $model_data->union_code;
                $plant_code[] = $model_data->plant_code;
            }
            $applicability_type = 3;
        }
        if ($is_string) {
            $dcs_code = implode('\',\'', $dcs_code);
            $bmc_code = implode('\',\'', $bmc_code);
            $mcc_plant_code = implode('\',\'', $mcc_plant_code);
            $plant_code = implode('\',\'', $plant_code);
            $dcs_code = !empty($dcs_code) ? '\'' . $dcs_code . '\'' : $dcs_code;
            $bmc_code = !empty($bmc_code) ? '\'' . $bmc_code . '\'' : $bmc_code;
            $mcc_plant_code = !empty($mcc_plant_code) ? '\'' . $mcc_plant_code . '\'' : $mcc_plant_code;
            $plant_code = !empty($plant_code) ? '\'' . $plant_code . '\'' : $plant_code;
        }
        return ['dcs_code' => $dcs_code, 'bmc_code' => $bmc_code, 'mcc_plant_code' => $mcc_plant_code, 'plant_code' => $plant_code, 'union_code' => $union_code, 'model_data' => $model_data, 'applicability_type' => $applicability_type, 'eipl_code' => $eipl_code];
    }
}
