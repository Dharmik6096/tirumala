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
use app\modules\organisation\models\TblDcsBmc;
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
        $DcsBmcModel = new TblDcsBmc();
        $area_data = $DcsBmcModel->getBMCList([], TRUE, FALSE, TRUE);
        unset($area_data[$id]);
        $model = new TblAreaBmcMapping();
        $searchModel = new TblAreaBmcMappingSearch();
        $searchModel->area_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $exist_data = ArrayHelper::map($dataProvider->getModels(), 'bmc_code', 'bmc_code');
        $area_data = array_diff_key($area_data, $exist_data);

        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['TblAreaBmcMapping'])) {
            $bmc_code = Yii::$app->request->post()['TblAreaBmcMapping']['bmc_code'];
            $mcc_codes = [];
            $master = [];
            if (!empty($bmc_code)) {
                foreach ($bmc_code as $mapped_bmc_code) {
                    $model_bmc = new TblAreaBmcMapping();
                    $model_bmc->area_code = $id;
                    $model_bmc->bmc_code = $mapped_bmc_code;
                    $mcc_codes[] = $model_bmc->bmcCode->mcc_plant_code;
                    $master[] = $model_bmc;
                    $mainBmc = $model_bmc->mainBmcCode;
                    $groupBmc = $model_bmc->bmcCode;
                    $main_org_data = ['union_code' => $mainBmc->union_code, 'plant_code' => $mainBmc->plant_code, 'mcc_plant_code' => $mainBmc->mcc_plant_code, 'bmc_code' => $id];
                    $group_org_data = ['union_code' => $groupBmc->union_code, 'plant_code' => $groupBmc->plant_code, 'mcc_plant_code' => $groupBmc->mcc_plant_code, 'bmc_code' => $mapped_bmc_code];
                    Yii::$app->general->generateGroupMappingSetBox($master, $main_org_data, $group_org_data);
                }
            }
            $transaction = $this->generalModel->saveTransaction($master, ['BMC Mapping', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }
        return $this->render('_bmc_mapping', [
                    'model' => $model,
                    'area_data' => $area_data,
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

}
