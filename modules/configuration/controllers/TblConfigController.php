<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblConfig;
use app\modules\configuration\models\TblConfigSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\configuration\models\TblUnionConfigResult;
use app\modules\configuration\models\TblConfigResult;
use app\components\Model;
use yii\helpers\Json;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\modules\configuration\models\TblUnionConfigResultHistory;
use app\modules\configuration\models\TblConfigMapping;
use app\modules\configuration\models\TblConfigMappingHistory;

/**
 * TblConfigController implements the CRUD actions for TblConfig model.
 */
class TblConfigController extends \app\controllers\ChildController {

    public $freeAccessActions = ['config-process-list'];

    /**
     * Lists all TblConfig models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblConfig model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $this->model = new TblConfig();
        $this->model->union_code = $id;
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Union Config', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetMasterData() {
        $code = Yii::$app->request->get('config_for');
        $unionCode = Yii::$app->request->get('union_code');
        $configModel = new TblConfig();
        $masterData = $configModel->getConfigData($code);
        $model = [];
        $saveModel = [];
        foreach ($masterData as $m) {
            $saveModel = new TblUnionConfigResult();
            $saveModel->config_code = $m->config_code;
            $saveModel->config_for = $code;
            $saveModel->union_code = $unionCode;
            $saveModelData = $saveModel->getExistConfig();
            if (!empty($saveModelData)) {
                $model[] = $saveModelData;
            } else {
                $model[] = $saveModel;
            }
        }

        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $postData = $data['TblUnionConfigResult'];

            $master = [];
            $childModel = [];
            $isValid = true;
            foreach ($postData as $value) {
                $saveModel = new TblUnionConfigResult();
                if (!empty($value['config_txn_code'])) {
                    $trnsModel = $saveModel->findOne($value['config_txn_code']);
                    if (!empty($trnsModel)) {
                        $historyModel = new TblUnionConfigResultHistory();
                        Yii::$app->operation->history($trnsModel, $historyModel, UPDATE);
                        $childModel[] = $historyModel;
                        $saveModel = $trnsModel;
                    }
                }
                $saveModel->config_code = $value['config_code'];
                $saveModel->config_result_key = $value['config_result_key'];
                $saveModel->config_name = $saveModel->configCode->config_name;
                $saveModel->config_key = $saveModel->configCode->config_key;
                $saveModel->config_for = $saveModel->configCode->config_for;
                $dropName = Yii::$app->general->getforeignkey($saveModel->configResultCode, 'config_result');
                $droptKey = Yii::$app->general->getforeignkey($saveModel->configResultCode, 'config_result_code');
                $textKey = Yii::$app->general->getforeignkey($saveModel->configResult, 'config_result_code');
                $saveModel->config_result_code = !empty($droptKey) ? $droptKey : $textKey;
                $saveModel->config_result = !empty($droptKey) ? $dropName : $value['config_result_key'];
                $saveModel->union_code = $value['union_code'];
                if (!$saveModel->validate()) {
                    $isValid = false;
                    break;
                }
                $master[] = $saveModel;
            }
            if ($isValid) {
                $transaction = $this->generalModel->saveTransaction($master, $childModel, ['Config', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['/organisation/tbl-unions/index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($saveModel));
            }
        }
        if (!empty($model)) {
            return $this->renderAjax('_input_form', [
                        'masterData' => $masterData,
                        'saveModel' => $saveModel,
                        'model' => $model,
            ]);
        }
    }

    public function actionConfigProcessList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $configFor = explode(',', $parents[0]);
                $inputAllow = isset($parents[1]) ? $parents[1] : '';
                $config = new TblConfig();
                $data = $config->getProcessList($configFor, $inputAllow);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionPaymentConfigCreate($id) {
        $this->model = new TblConfig();
        $this->model->union_code = $id;
        $this->model->config_for = 'BMC,PLANT';
        $this->viewFile = 'payment_create';
        $this->model->scenario = 'PaymentConfig';
        if ($this->model->load(Yii::$app->request->post())) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Vendor Payment config', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render('payment_create', [
                    'model' => $this->model,
                    'config_param' => $this->model->config_for
        ]);
    }

    public function actionPaymentConfigData() {
        $code = Yii::$app->request->get('config_for');
        $process = Yii::$app->request->get('process');
        $unionCode = Yii::$app->request->get('union_code');
        $plant = Yii::$app->request->get('plant');
        $mcc = Yii::$app->request->get('mcc');
        $bmc = Yii::$app->request->get('bmc');
        $org_code = $code == 'BMC' ? $bmc : $plant;
        $configModel = new TblConfig();
        $configModel->config_for = $code;
        $configModel->config_type = array('CONTROL', 'CONFIG');
        $configModel->process_name = $process;
        $configModel->is_input_config = 1;
        $masterData = $configModel->getConfigDetail();
        $saveModel = [];
        $model = [];

        foreach ($masterData as $m) {
            $saveModel = new TblConfigMapping();
            $saveModel->config_code = $m->config_code;
            $saveModel->union_code = $unionCode;
            $saveModel->plant_code = $plant;
            $saveModel->mcc_plant_code = !empty($mcc) ? $mcc : '';
            $saveModel->bmc_code = !empty($bmc) ? $bmc : '';
            $saveModel->org_code = $org_code;
            $saveModel->org_type = $code;
            $saveModelData = $saveModel->getExistConfig();
            if (!empty($saveModelData)) {
                $model[] = $saveModelData;
            } else {
                $model[] = $saveModel;
            }
        }

        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $postData = $data['TblConfigMapping'];

            $master = [];
            $childModel = [];
            foreach ($postData as $value) {
                $saveModel = new TblConfigMapping();
                if (!empty($value['config_mapping_code'])) {
                    $trnsModel = $saveModel->findOne($value['config_mapping_code']);
                    if (!empty($trnsModel)) {
                        $historyModel = new TblConfigMappingHistory();
                        Yii::$app->operation->history($trnsModel, $historyModel, UPDATE);
                        $childModel[] = $historyModel;
                        $saveModel = $trnsModel;
                    }
                }

                $saveModel->config_code = $value['config_code'];
                $saveModel->config_result = $value['config_result'];
                $saveModel->config_result = $value['config_result'];
                $saveModel->union_code = $value['union_code'];
                $saveModel->plant_code = $value['plant_code'];
                $saveModel->mcc_plant_code = $value['mcc_plant_code'];
                $saveModel->bmc_code = $value['bmc_code'];
                $saveModel->org_code = $value['org_code'];
                $saveModel->org_type = $value['org_type'];
                $saveModel->config_for = $value['org_type'];

                $master[] = $saveModel;
            }
            if ($saveModel->validate()) {
                $transaction = $this->generalModel->saveTransaction($master, $childModel, ['Payment Configurations', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['/organisation/tbl-unions/index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($saveModel));
            }
        }
        return $this->renderAjax('payment_input_form', [
                    'masterData' => $masterData,
                    'saveModel' => $saveModel,
                    'model' => $model,
        ]);
    }

}
