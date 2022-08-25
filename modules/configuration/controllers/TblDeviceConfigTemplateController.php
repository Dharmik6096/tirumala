<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblDeviceConfigTemplate;
use app\modules\configuration\models\TblDeviceConfigTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\configuration\models\TblDeviceConfigMaster;
use app\modules\configuration\models\TblDeviceConfigTemplateDetails;
use app\modules\configuration\models\TblDeviceConfigTemplateDetailsHistory;
use yii\helpers\Json;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\modules\configuration\models\TblDeviceConfigTempMapping;
use app\modules\configuration\models\TblDeviceConfigTempMappingHistory;

/**
 * TblDeviceConfigTemplateController implements the CRUD actions for TblDeviceConfigTemplate model.
 */
class TblDeviceConfigTemplateController extends \app\controllers\ChildController {

    /**
     * Lists all TblDeviceConfigTemplate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDeviceConfigTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDeviceConfigTemplate model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblDeviceConfigTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $templateModel = new TblDeviceConfigTemplate();
        $configModel = new TblDeviceConfigMaster();
        $masterData = $configModel->getConfigData();
        $model = [];
        foreach ($masterData as $m) {
            $saveModel = new TblDeviceConfigTemplateDetails();
            $saveModel->device_config_code = $m->device_config_code;
            $model[] = $saveModel;
        }
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $postTempData = $data['TblDeviceConfigTemplate'];
            $postConfigData = $data['TblDeviceConfigTemplateDetails'];

            $master = [];
            $childModel = [];

            $templateModel = new TblDeviceConfigTemplate();
            $templateModel->device_temp_code = Yii::$app->general->getCodeAutoIncrement($templateModel);
            $templateModel->device_temp_name = !empty($postTempData['device_temp_name']) ? $postTempData['device_temp_name'] : '';
            $templateModel->union_code = !empty($postTempData['union_code']) ? $postTempData['union_code'] : '';
            $master[] = $templateModel;
            foreach ($postConfigData as $value) {
                $saveModel = new TblDeviceConfigTemplateDetails();
                if (!empty($value['device_config_template_details_code'])) {
                    $trnsModel = $saveModel->findOne($value['device_config_template_details_code']);
                    if (!empty($trnsModel)) {
                        $historyModel = new TblDeviceConfigTemplateDetailsHistory();
                        Yii::$app->operation->history($trnsModel, $historyModel, UPDATE);
                        $childModel[] = $historyModel;
                        $saveModel = $trnsModel;
                    }
                }
                $saveModel->device_temp_code = $templateModel->device_temp_code;
                $saveModel->device_config_code = $value['device_config_code'];
                $saveModel->config_type_code = $value['config_type_code'];
                $saveModel->device_config_name = $saveModel->configCode->device_config_name;
                $saveModel->device_config_key = $saveModel->configCode->device_config_key;
                $dropName = Yii::$app->general->getforeignkey($saveModel->configResultCode, 'config_type_value');
                $droptKey = Yii::$app->general->getforeignkey($saveModel->configResultCode, 'device_config_txn_code');
                $textKey = Yii::$app->general->getforeignkey($saveModel->configResult, 'device_config_txn_code');
                $saveModel->device_config_txn_code = !empty($droptKey) ? $droptKey : $textKey;
                $saveModel->config_type_value = !empty($droptKey) ? $dropName : $value['config_type_code'];
                $saveModel->union_code = $templateModel->union_code;
                $master[] = $saveModel;
            }

            if ($templateModel->validate()) {
                $transaction = $this->generalModel->saveTransaction($master, $childModel, ['Config', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['/configuration/tbl-device-config-template/index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($templateModel));
            }
        }
        return $this->render('create', [
                    'masterData' => $masterData,
                    'saveModel' => $saveModel,
                    'model' => $model,
                    'templateModel' => $templateModel,
        ]);
    }

    /**
     * Updates an existing TblDeviceConfigTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $templateModel = $this->findModel($id);

        $configModel = new TblDeviceConfigMaster();
        $masterData = $configModel->getConfigData();
        $model = [];
        foreach ($masterData as $m) {
            $saveModel = new TblDeviceConfigTemplateDetails();
            $saveModel->device_config_code = $m->device_config_code;
            $saveModel->device_temp_code = $id;
            $saveModelData = $saveModel->getExistConfig();
            if (!empty($saveModelData)) {
                $model[] = $saveModelData;
            } else {
                $model[] = $saveModel;
            }
        }

        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $postTempData = $data['TblDeviceConfigTemplate'];
            $postConfigData = $data['TblDeviceConfigTemplateDetails'];

            $master = [];
            $childModel = [];

            $templateModel->device_temp_name = !empty($postTempData['device_temp_name']) ? $postTempData['device_temp_name'] : '';
            $templateModel->union_code = !empty($postTempData['union_code']) ? $postTempData['union_code'] : '';
            $master[] = $templateModel;
            foreach ($postConfigData as $value) {
                $saveModel = new TblDeviceConfigTemplateDetails();
                if (!empty($value['device_config_template_details_code'])) {
                    $trnsModel = $saveModel->findOne($value['device_config_template_details_code']);
                    if (!empty($trnsModel)) {
                        $historyModel = new TblDeviceConfigTemplateDetailsHistory();
                        Yii::$app->operation->history($trnsModel, $historyModel, UPDATE);
                        $childModel[] = $historyModel;
                        $saveModel = $trnsModel;
                    }
                }
                $saveModel->device_temp_code = $templateModel->device_temp_code;
                $saveModel->device_config_code = $value['device_config_code'];
                $saveModel->config_type_code = $value['config_type_code'];
                $saveModel->device_config_name = $saveModel->configCode->device_config_name;
                $saveModel->device_config_key = $saveModel->configCode->device_config_key;
                $dropName = Yii::$app->general->getforeignkey($saveModel->configResultCode, 'config_type_value');
                $droptKey = Yii::$app->general->getforeignkey($saveModel->configResultCode, 'device_config_txn_code');
                $textKey = Yii::$app->general->getforeignkey($saveModel->configResult, 'device_config_txn_code');
                $saveModel->device_config_txn_code = !empty($droptKey) ? $droptKey : $textKey;
                $saveModel->config_type_value = !empty($droptKey) ? $dropName : $value['config_type_code'];
                $saveModel->union_code = $templateModel->union_code;
                $master[] = $saveModel;
            }

            if ($templateModel->validate()) {
                $transaction = $this->generalModel->saveTransaction($master, $childModel, ['Config', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['/configuration/tbl-device-config-template/index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($templateModel));
            }
        }
        return $this->render('update', [
                    'masterData' => $masterData,
                    'saveModel' => $saveModel,
                    'model' => $model,
                    'templateModel' => $templateModel,
        ]);
    }

    /**
     * Deletes an existing TblDeviceConfigTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblDeviceConfigTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDeviceConfigTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDeviceConfigTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeviceConfigMappingApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblDeviceConfigTempMapping();
        $value = [];
        $value['DCS'] = 'DCS';
        $value['BMC'] = 'BMC';
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'device_temp_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'Device Config Mapping';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $appModel->header_title = ' (' . $model->device_temp_name . ')';

        $appModel->fields = [
            'bmc_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Parent Code'), 'value' => function($model) {
                    if (strtolower($model->applicable_for) == 'dcs') {
                        return $name = Yii::$app->general->getforeignkey($model->dcsCode, 'bmc_code');
                    } else {
                        return $name = Yii::$app->general->getforeignkey($model->bmcCode, 'mcc_plant_code');
                    }
                }],
            'bmc_name' => ['view' => ['grid'], 'label' => Yii::t('app', 'Parent Name'), 'value' => function($model) {
                    if (strtolower($model->applicable_for) == 'dcs') {
                        return $name = Yii::$app->general->getmultiforeignkey($model->dcsCode, ['bmcCode'], 'bmc_name');
                    } else {
                        return $name = Yii::$app->general->getmultiforeignkey($model->bmcCode, ['tblMccPlant'], 'name');
                    }
                }],

            'applicable_for' => ['view' => ['grid'], 'value' => 'applicable_for'],
            'applicable_code' => ['view' => ['grid'], 'value' => 'applicable_code'],
            'applicable_name' => ['view' => ['grid'], 'label' => Yii::t('app', 'Applicable Name'), 'value' => function($model) {
                    if (strtolower($model->applicable_for) == 'dcs') {
                        return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
                    } else {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                    }
                }],
        ];
        $appModel->actions = [
            'delete' => ['option' => 'applicable_code,config_temp_mapping_code,tbl-device-config-template/delete-mapping'],
        ];

        $appModel->ratechart = FALSE;
        $appModel->dcs_filters = $value;
        $appModel->check_wef_date = FALSE;
        $appModel->generateMail = FALSE;
        $appModel->isApproval = FALSE;
        return $appModel->createApp();
    }

    public function actionDeleteMapping() {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $detailHistory = new TblDeviceConfigTempMappingHistory();
            $record = TblDeviceConfigTempMapping::find()->where(['config_temp_mapping_code' => Yii::$app->request->post('id')])->one();
            Yii::$app->operation->history($record, $detailHistory, DELETE);
            $master[] = $detailHistory->save(FALSE);
            $master[] = $record->delete();
            if (in_array(FALSE, $master)) {
                $transaction->rollback();
                $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
            } else {
                $transaction->commit();
                $record = ['status' => 'success', 'msg' => 'Record is successfuly deleted.'];
            }
        } catch (UserException $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => $e->getMessage()];
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            $record = ['status' => 'error', 'msg' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
