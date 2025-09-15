<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblMccPlantSearch;
use app\modules\organisation\models\TblMccPlantHistory;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcsBmcSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\organisation\models\TblMccMilkType;
use app\modules\organisation\models\TblMccMilkTypeHistory;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblMccPlantGroupMapping;
use app\modules\organisation\models\TblMccPlantGroupMappingSearch;
use app\modules\organisation\models\TblMccPlantGroupMappingHistory;
use app\modules\organisation\models\TblBmcSilosInfo;
use app\modules\organisation\models\TblBmcSilosInfoSearch;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\document\controllers\TblAttachmentController;

/**
 * TblMccPlantController implements the CRUD actions for TblMccPlant model.
 */
class TblMccPlantController extends \app\controllers\ChildController {

    public $contactDetails;
    public $freeAccessActions = ['mcc-list', 'get-plant-mcc', 'union-mcc-list', 'places-list'];

    /**
     * Lists all TblMccPlant models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMccPlantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMccPlant model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
//        $csearchModel = new TblContactDetailsSearch();
//        $csearchModel->module_name='society';
//        $csearchModel->module_code=$id;
//        $cdataProvider= $csearchModel->search(Yii::$app->request->queryParams);
//        return $this->render('view', [
//                    'model' => $this->findModel($id),'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
//        ]);

        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'mccPlant';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);

        $bmcsearchModel = new TblDcsBmcSearch();
        $bmcsearchModel->mcc_plant_code = $id;
        $bmcdataProvider = $bmcsearchModel->bccSearch(Yii::$app->request->queryParams);

        $snsearchModel = new TblBmcSilosInfoSearch();
        $snsearchModel->module_name = 'MCC';
        $snsearchModel->module_code = $id;
        $sndataProvider = $snsearchModel->search(Yii::$app->request->queryParams);
        $isaction = FALSE;
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
                    'bmcdataProvider' => $bmcdataProvider, 'bmcsearchModel' => $bmcsearchModel,
                    'sndataProvider' => $sndataProvider, 'snsearchModel' => $snsearchModel, 'isaction' => $isaction
        ]);
    }

    /**
     * Creates a new TblMccPlant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMccPlant();
        $this->viewFile = 'create';
        $this->contactDetails = new TblContactDetails();
        $this->model->valid_from = date('Y-m-d');
        $this->contactDetails->scenario = 'additional';
        $this->contactDetails->form_validation_type = 'mcc-create';
        $this->contactDetails->department = 'mcc_incharge';
        $validate = 1;
        $master = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel($this->model);
            $this->model->mcc_plant_code = $this->model->getCode();
            $this->model->name = ucwords($this->model->name);
//            $bmcModel = new TblDcsBmc();
//            $bmcModel->scenario = 'from_mcc';
//            $bmcModel->mcc_code = $this->model->mcc_plant_code;
//            $bmcModel->bmc_code = $bmcModel->getCode();
//            $bmcModel->bmc_name = $this->model->name;
//            $bmcModel->local_name = $this->model->local_name;
//            $bmcModel->capacity = $this->model->capacity;
//            $bmcModel->is_active = $this->model->is_active;
//            $bmcModel->state_code = $this->model->state_code;
//            $bmcModel->district_code = $this->model->district_code;
//            $bmcModel->sub_district_code = $this->model->sub_district_code;
//            $bmcModel->village_code = $this->model->village_code;
//            $bmcModel->hamlet_code = $this->model->hamlet_code;
//            $bmcModel->union_code = $this->model->union_code;
//            $bmcModel->valid_from = $this->model->valid_from;
//            $bmcModel->is_mcc = 1;
            $this->contactDetails->load(Yii::$app->request->post());
            $this->contactDetails->setModel('mccPlant', $this->model->mcc_plant_code);
            $master[] = $this->model;
            $modelMilkType = $this->setMilk();
            if (!empty($modelMilkType)) {
                $master = array_merge($master, $modelMilkType);
            }

            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique($this->model, 'name', $this->model->name);
            if ($validate == 1 && empty($this->model->getErrors())) {
                $transaction = $this->generalModel->saveTransaction($master, [$this->contactDetails], ['MCC', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblMccPlant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;
        $master = [];
        if (Yii::$app->request->post()) {

            $historyModel = new TblMccPlantHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            $this->model->name = ucwords($this->model->name);
            $milkTypes = TblMccMilkType::findAll(['mcc_plant_code' => $this->model->mcc_plant_code]);
            foreach ($milkTypes as $milkModel) {
                $milkHistory = new TblMccMilkTypeHistory();
                Yii::$app->operation->history($milkModel, $milkHistory, DELETE);
                $master[] = $milkHistory;
                $master[] = $milkModel;
            }
            foreach ($this->model->milk_type_code as $value) {
                $milkModel = new TblMccMilkType();
                $milkModel->mcc_plant_code = $this->model->mcc_plant_code;
                $milkModel->milk_type_code = $value;
                $milkModel->is_active = $this->model->is_active;
                $master[] = $milkModel;
            }
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'name', $_POST['TblMccPlant']['name']);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], $master, ['MCC', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblMccPlant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_mcc_plant', Yii::$app->request->post('id'), 'mcc_plant_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblMccPlantHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
//             $record = $this->generalModel->deleteTransaction([$this->model,$historyModel]);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], [false, 'TblContactDetails', 'TblContactDetailsHistory'], ['mcc_plant_code', 'mccPlant']);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionContactDetails($id) {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'mccPlant';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'mccPlant',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'mail_info' => TRUE
        ]);
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->mcc_plant_code]);
    }

    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
                    'contactDetails' => $this->contactDetails
        ]);
    }

    private function setModel() {
        $this->model->valid_from = ($this->model->valid_from == '') ? null : Yii::$app->formatter->asDate($this->model->valid_from, DATE_FORMAT);
    }

    /**
     * Finds the TblMccPlant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMccPlant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMccPlant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMccList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $mccs = new TblMccPlant();
                $data = $mccs->getMCCList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetPlantMcc() {
        $mccList = [];
        if (!empty($_POST['plant'])) {
            $palnt = explode(',', $_POST['plant']);
            $RLS = $_POST['RLS'];
            $model = new TblMccPlant();
            $mccList = $model->getMCCList($palnt, $RLS);
        }
        return Json::encode(['status' => 'success', 'data' => $mccList]);
    }

    private function setMilk() {
        $milkArray = $this->model->milk_type_code;
        $list = [];
        foreach ($milkArray as $row) {
            $modelMilk = new TblMccMilkType();
            $modelMilk->mcc_plant_code = $this->model->mcc_plant_code;
            $modelMilk->milk_type_code = $row;
            $modelMilk->is_active = 1;
            array_push($list, $modelMilk);
        }
        return $list;
    }

    public function actionMccMapping($id) {
        $MccModel = new TblMccPlant();
        $mcc_data = $MccModel->getMCCList([]);
        unset($mcc_data[$id]);
        $model = new TblMccPlantGroupMapping();
        $searchModel = new TblMccPlantGroupMappingSearch();
        $searchModel->mcc_plant_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $exist_data = ArrayHelper::map($dataProvider->getModels(), 'p_mcc_plant_code', 'p_mcc_plant_code');
        $mcc_data = array_diff_key($mcc_data, $exist_data);
        if (Yii::$app->request->post() && isset(Yii::$app->request->post()['TblMccPlantGroupMapping'])) {
            $mcc_code = Yii::$app->request->post()['TblMccPlantGroupMapping']['p_mcc_plant_code'];
            $master = [];
            foreach ($mcc_code as $mapped_mcc_code) {
                $model_mcc = new TblMccPlantGroupMapping();
                $model_mcc->mcc_plant_code = $id;
                $model_mcc->p_mcc_plant_code = $mapped_mcc_code;
                $master[] = $model_mcc;
                $mainMcc = $model_mcc->mainMccCode;
                $groupMcc = $model_mcc->mccCode;
                $main_org_data = ['union_code' => $mainMcc->union_code, 'plant_code' => $mainMcc->plant_code, 'mcc_plant_code' => $id];
                $group_org_data = ['union_code' => $groupMcc->union_code, 'plant_code' => $groupMcc->plant_code, 'mcc_plant_code' => $mapped_mcc_code];
                Yii::$app->general->generateGroupMappingSetBox($master, $main_org_data, $group_org_data, 'mcc_plant_code', 'MCC');
            }
            $transaction = $this->generalModel->saveTransaction($master, ['MCC Mapping', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }

        return $this->render('_mcc_mapping', [
                    'model' => $model, 'mcc_data' => $mcc_data,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    public function actionDeleteMcc() {
        $model = TblMccPlantGroupMapping::findOne(Yii::$app->request->post('id'));
        $record = [];
        $historyModel = new TblMccPlantGroupMappingHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionUnionMccList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) || (isset($parents[1]) && $parents[1] == true)) {
                $rls = isset($parents[1]) && $parents[1] == 'false' ? 'FALSE' : 'TRUE';
                $mccs = new TblMccPlant();
                $data = $mccs->getUnionMCCList($parents[0], $rls);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionSilosInfo($id) {
        $contactDetails = new TblBmcSilosInfo();
        $searchModel = new TblBmcSilosInfoSearch();
        $searchModel->module_name = 'MCC';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $isaction = TRUE;
        return $this->render('@app/modules/organisation/views/tbl-bmc-silos-info/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'MCC',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'isaction' => $isaction
        ]);
    }

    public function actionPlacesList() {
        $out = [];
        $u = '';
        $data = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                $union = $parents[0];
                $type = $parents[1];
                $setChild = isset($parents[2]) ? TRUE : FALSE;
            } else {
                echo Json::encode(['output' => '', 'selected' => '']);
                return;
            }
            if ($type == 'bmc') {
                $mccs = new TblDcsBmc();
                $data = $mccs->getBmcs($union, [], false);
            } else if ($type == 'mcc') {
                $mccs = new TblMccPlant();
                $data = $mccs->getUnionMCCList($union);
            } else if ($type == 'plant') {
                $mccs = new \app\modules\organisation\models\TblPlant();
                $data = $mccs->getPlantList($union, 'TRUE', [], false);
            } else if ($type == 'vendor') {
                $vendors = new TblCustomerMaster();
//                $vendors->customer_type = 'VENDOR';
                $data = $vendors->getUnionCustomerList($union);
            }
            foreach ($data as $key => $val) {
                $out[] = array('id' => $key, 'name' => $val);
            }
            return Json::encode(['output' => $out, 'selected' => '']);
            return;
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionMccDocumentUpload($id) {
        $model = $this->findModel($id);
        $module_code = $model->mcc_plant_code;
        $module_name = 'tbl_mcc_plant';
        $val = new TblAttachmentController($this->id, $this->module);
        return $val->actiondocumentUpload('mcc', $id, $model, $module_code, $module_name);
    }
    
    public function actionGetUnionMcc() {
        $mccList = [];
        if (!empty($_POST['union'])) {
            $union = $_POST['union'];
            $RLS = $_POST['RLS'];
            $mccs = new TblMccPlant();
            $mccList = $mccs->getUnionMCCList($union, $RLS);
        }
        return Json::encode(['status' => 'success', 'data' => $mccList]);
    }

}
