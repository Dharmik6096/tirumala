<?php

namespace app\modules\geo\controllers;

use Yii;
use app\modules\geo\models\TblArea;
use app\modules\geo\models\TblAreaHistory;
use app\modules\geo\models\TblAreaSearch;
use yii\web\NotFoundHttpException;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\controllers\ChildController;
use yii\helpers\ArrayHelper;
use app\modules\geo\models\TblAreaBmcMapping;
use app\modules\geo\models\TblAreaBmcMappingHistory;
use app\modules\geo\models\TblAreaBmcMappingSearch;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use yii\helpers\Html;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblAreaController implements the CRUD actions for TblArea model.
 */
class TblAreaController extends ChildController {

    public $contactDetails, $freeAccessActions = ['area-bmc-list', 'area-list'];

    /**
     * Lists all TblArea models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAreaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblArea model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'area';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
        ]);
    }

    /**
     * Creates a new TblArea model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $this->model = new TblArea();
        $this->viewFile = 'create';
        $this->contactDetails = new TblContactDetails();
        $this->contactDetails->scenario = 'additional';
        $this->contactDetails->form_validation_type = 'area-create';
        $validate = 1;
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->area_name = ucwords($this->model->area_name);

            $this->contactDetails->load(Yii::$app->request->post());
            $this->contactDetails->setModel('area', $this->model->area_code);
            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique($this->model, 'area_name', $this->model->area_name);
            if ($validate == 1) {
                $auto_key_config['TblContactDetails'][] = ['self_key' => 'module_code', 'parent_key' => 'area_code', 'parent_index' => 0];
                $transaction = $this->generalModel->saveTransactionAutoIncForeignKey([$this->model, $this->contactDetails], ['Area Detail', 'create'], $auto_key_config);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->render('create', ['model' => $this->model,
                    'contactDetails' => $this->contactDetails
        ]);
    }

    /**
     * Updates an existing TblArea model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;

        if (Yii::$app->request->post()) {

            $historyModel = new TblAreaHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->model->area_name = ucwords($this->model->area_name);
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'area_name', $_POST['TblArea']['area_name']);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Area', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblArea model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblAreaHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], [false, 'TblContactDetails', 'TblContactDetailsHistory'], ['area_code', 'area']);
    }

    /**
     * Finds the TblArea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblArea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblArea::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionContactDetails($id) {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'area';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'area',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionAreaMapping($id) {
        $model = new TblAreaBmcMapping();
        $model->applicable_type = Yii::$app->request->post('applicable_type', Yii::$app->request->get('applicable_type', 'BMC'));

        $searchModel = new TblAreaBmcMappingSearch();
        $searchModel->area_code = $id;
        $searchModel->applicable_type = $model->applicable_type;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['TblAreaBmcMapping'])) {
            $applicable_code = isset(Yii::$app->request->post()['TblAreaBmcMapping']['applicable_code']) ? Yii::$app->request->post()['TblAreaBmcMapping']['applicable_code'] : [];
            $mcc_codes = [];
            $master = [];
            if (!empty($applicable_code)) {
                foreach ($applicable_code as $mapped_code) {
                    $model_bmc = new TblAreaBmcMapping();
                    $model_bmc->area_code = $id;
                    $model_bmc->applicable_type = $model->applicable_type;
                    if ($model->applicable_type == 'DCS') {
                        $model_bmc->applicable_code = $mapped_code;
                        $model_bmc->bmc_code = null;
                        $master[] = $model_bmc;
                    } else {
                        $model_bmc->applicable_code = $mapped_code;
                        $model_bmc->bmc_code = $mapped_code;
                        $mcc_codes[] = $model_bmc->bmcCode->mcc_plant_code;
                        $master[] = $model_bmc;
                        $mainBmc = $model_bmc->mainBmcCode;
                        $groupBmc = $model_bmc->bmcCode;
                        $main_org_data = ['union_code' => $mainBmc->union_code, 'plant_code' => $mainBmc->plant_code, 'mcc_plant_code' => $mainBmc->mcc_plant_code, 'bmc_code' => $id];
                        $group_org_data = ['union_code' => $groupBmc->union_code, 'plant_code' => $groupBmc->plant_code, 'mcc_plant_code' => $groupBmc->mcc_plant_code, 'bmc_code' => $mapped_code];
                        Yii::$app->general->generateGroupMappingSetBox($master, $main_org_data, $group_org_data);
                    }
                }
            }
            $transaction = $this->generalModel->saveTransaction($master, ['Area Mapping', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['area-mapping', 'id' => $id, 'applicable_type' => $model->applicable_type]);
            }
        }
        return $this->render('_bmc_mapping', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    public function actionDeleteBmc() {
        $model = TblAreaBmcMapping::findOne(Yii::$app->request->post('id'));
        $record = [];
        $historyModel = new TblAreaBmcMappingHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionAreaBmcList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $mccs = new TblAreaBmcMapping();
                $data = $mccs->getAreaBmcList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionAreaList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $areaModel = new TblArea();
                $data = $areaModel->getRegionAreaList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetApplicabilityData() {
        $id = Yii::$app->request->post('id');
        $applicable_type = Yii::$app->request->post('applicable_type');

        $searchModel = new TblAreaBmcMappingSearch();
        $searchModel->applicable_type = $applicable_type;
        $dataProvider = $searchModel->search([]);
        $dataProvider->pagination = false;
        $allModels = $dataProvider->getModels();

        $exist_data = [];
        if ($applicable_type == 'DCS') {
            $DcsBmcModel = new TblDcsBmc();
            $area_data = $DcsBmcModel->getBMCList([], TRUE, FALSE, TRUE);
            $name = 'bmc_filter[]';
            $classPrefix = 'bmc-filter';
        } else {
            $exist_data = ArrayHelper::map($allModels, 'bmc_code', 'bmc_code');
            $DcsBmcModel = new TblDcsBmc();
            $area_data = $DcsBmcModel->getBMCList([], TRUE, FALSE, TRUE);
            unset($area_data[$id]);
            $area_data = array_diff_key($area_data, $exist_data);
            $name = 'TblAreaBmcMapping[applicable_code][]';
            $classPrefix = 'data';
        }
        $searchModel->area_code = $id;
        $dataProvider->setModels(array_filter($allModels, function($model) use ($id) {
            return $model->area_code == $id;
        }));

        $html = '';
        foreach ($area_data as $value => $label) {
            $checkbox = Html::checkbox($name, false, [
                'value' => $value,
                'label' => '<label for=' . $classPrefix . '-' . $value . '>' . $label . '</label>',
                'labelOptions' => [
                    'class' => 'route-text',
                ],
                'class' => $classPrefix . '-checkbox route-checkbox',
                'id' => $classPrefix . '-' . $value,
            ]);
            $divClass = ($applicable_type == 'DCS') ? 'col-sm-12' : 'col-sm-2';
            $html .= "<div class='{$divClass} checklist {$classPrefix}-checklist'><div class='checkbox'>{$checkbox}</div></div>";
        }

        $gridHtml = $this->renderPartial('_mapped_bmc', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['status' => 'success', 'data' => $html, 'grid' => $gridHtml];
    }

    public function actionGetDcsByBmc() {
        $id = Yii::$app->request->post('id');
        $selected_bmc = Yii::$app->request->post('selected_bmc', []);
        
        $searchModel = new TblAreaBmcMappingSearch();
        $searchModel->applicable_type = 'DCS';
        $dataProvider = $searchModel->search([]);
        $dataProvider->pagination = false;

        $allModels = $dataProvider->getModels();
        $exist_data = ArrayHelper::map($allModels, 'applicable_code', 'applicable_code');

        $searchModel->area_code = $id;
        $dataProvider->setModels(array_filter($allModels, function($model) use ($id) {
            return $model->area_code == $id;
        }));

        $areaModel = TblArea::findOne($id);
        $unionCode = $areaModel ? $areaModel->union_code : '';
        
        $query = TblDcs::find()
            ->select(['dcs_code', 'dcs_name'])
            ->where(['is_active' => 1]);
            
        if (!empty($unionCode)) {
            $query->andWhere(['union_code' => $unionCode]);
        }
        if (!empty($selected_bmc)) {
            $query->andWhere(['bmc_code' => $selected_bmc]);
        } else {
            $query->andWhere('0=1');
        }
        
        $dcsList = $query->all();
        $area_data = ArrayHelper::map($dcsList, 'dcs_code', 'dcs_name');
        $area_data = array_diff_key($area_data, $exist_data);
        
        $html = '';
        foreach ($area_data as $value => $label) {
            $name = 'TblAreaBmcMapping[applicable_code][]';
            $checkbox = \yii\helpers\Html::checkbox($name, false, [
                'value' => $value,
                'label' => '<label for=data-' . $value . '>' . $label . '</label>',
                'labelOptions' => [
                    'class' => 'route-text',
                ],
                'class' => 'data-checkbox route-checkbox',
                'id' => 'data-' . $value,
            ]);
            $html .= "<div class='col-sm-4 checklist data-checklist'><div class='checkbox'>{$checkbox}</div></div>";
        }
        $gridHtml = $this->renderPartial('_mapped_bmc', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return ['status' => 'success', 'data' => $html, 'grid' => $gridHtml];
    }

    public function actionValidateMapping() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $applicable_type = Yii::$app->request->post('applicable_type');
        $applicable_code = Yii::$app->request->post('applicable_code', []);
        
        $errors = [];
        if (!empty($applicable_code)) {
            $existingMappings = TblAreaBmcMapping::find()
                ->with(['mainAreaCode'])
                ->where([
                    'applicable_type' => $applicable_type,
                    'applicable_code' => $applicable_code,
                    'is_active' => 1
                ])
                ->all();

            if (!empty($existingMappings)) {
                $existingMap = ArrayHelper::map($existingMappings, 'applicable_code', function($mapping) {
                    return $mapping;
                });
                $duplicateCodes = array_keys($existingMap);
                $names = [];

                if ($applicable_type == 'DCS') {
                    $dcsModels = TblDcs::find()->where(['dcs_code' => $duplicateCodes])->all();
                    $names = ArrayHelper::map($dcsModels, 'dcs_code', 'dcs_name');
                } else {
                    $bmcModels = TblDcsBmc::find()->where(['bmc_code' => $duplicateCodes])->all();
                    $names = ArrayHelper::map($bmcModels, 'bmc_code', 'bmc_name');
                }

                foreach ($existingMappings as $exists) {
                    $mapped_code = $exists->applicable_code;
                    $areaName = isset($exists->mainAreaCode) ? $exists->mainAreaCode->area_name : 'Unknown';
                    $entityName = isset($names[$mapped_code]) ? $names[$mapped_code] : $mapped_code;

                    if ($applicable_type == 'DCS') {
                        $errors[] = "DCS '{$entityName}' is already mapped to Area '{$areaName}'.";
                    } else {
                        $errors[] = "BMC '{$entityName}' is already mapped to Area '{$areaName}'.";
                    }
                }
            }
        }
        
        if (empty($errors)) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'errors' => $errors];
        }
    }
}
