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

/**
 * TblMccPlantController implements the CRUD actions for TblMccPlant model.
 */
class TblMccPlantController extends \app\controllers\ChildController {

    public $contactDetails;
    public $freeAccessActions = ['mcc-list', 'get-plant-mcc'];

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
        $bmcsearchModel->mcc_code = $id;
        $bmcdataProvider = $bmcsearchModel->bccSearch(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
                    'bmcdataProvider' => $bmcdataProvider, 'bmcsearchModel' => $bmcsearchModel,
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
        $validate = 1;

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
            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique($this->model, 'name', $this->model->name);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model], [$this->contactDetails], ['MCC', 'create']);
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

        if (Yii::$app->request->post()) {

            $historyModel = new TblMccPlantHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            $this->model->name = ucwords($this->model->name);
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'name', $_POST['TblMccPlant']['name']);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['MCC', 'edit']);
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
                    'dataProvider' => $dataProvider
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
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetPlantMcc() {
        $mccList = [];
        if (!empty($_POST['plant'])) {
            $palnt = explode(',', $_POST['plant']);
            $RLS = $_POST['RLS'];
            $model = new TblMccPlant();
            $mccList = $model->getMCCList($palnt, $RLS);
        }
        echo Json::encode(['status' => 'success', 'data' => $mccList]);
    }

}
