<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblVendorMaster;
use app\modules\product\models\TblVendorMasterHistory;
use app\modules\product\models\TblVendorMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsHistory;
use app\modules\details\models\TblContactDetailsSearch;
use ReflectionClass;
use app\models\ChildModel;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblVendorMasterController implements the CRUD actions for TblVendorMaster model.
 */
class TblVendorMasterController extends \app\controllers\ChildController {

    /**
     * Lists all TblVendorMaster models.
     * @return mixed
     */
    public $contactDetails;

    public function actionIndex() {
        $searchModel = new TblVendorMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVendorMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'vendor';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
        ]);
    }

    /**
     * Creates a new TblVendorMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVendorMaster();
        $this->viewFile = 'create';
        $this->contactDetails = new TblContactDetails();
        $this->contactDetails->scenario = 'additional';
        $master = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->vendor_master_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            array_push($master, $this->model);
            $this->contactDetails->load(Yii::$app->request->post());
            if (!empty($this->contactDetails->mobile_no)) {
                $this->contactDetails->setModel('vendor', $this->model->vendor_master_code);
                array_push($master, $this->contactDetails);
            }
            $transaction = $this->generalModel->saveTransaction($master, ['vendor', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }

        return $this->render($this->viewFile, ['model' => $this->model,
                    'contactDetails' => $this->contactDetails
        ]);
    }

    /**
     * Updates an existing TblVendorMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblVendorMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Vendor Master', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render($this->viewFile, ['model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblVendorMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $id = Yii::$app->request->post('id');
        $this->model = $this->findModel($id);
        $master = [];
        if (!empty($this->model)) {
            $historyModel = new TblVendorMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel], [false, 'TblContactDetails', 'TblContactDetailsHistory'], ['vendor_master_code', 'vendor']);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblVendorMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVendorMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVendorMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionContactDetails($id) {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'vendor';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'vendor',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

}
