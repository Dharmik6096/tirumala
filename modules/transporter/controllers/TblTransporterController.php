<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblTransporter;
use app\modules\transporter\models\TblTransporterSearch;
use app\modules\transporter\models\TblTransporterHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblBankDetailsSearch;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use yii\helpers\Url;
use app\modules\document\controllers\TblAttachmentController;

/**
 * TblTransporterController implements the CRUD actions for TblTransporter model.
 */
class TblTransporterController extends \app\controllers\ChildController {

    public $bankDetails;
    public $contactDetails;
    public $freeAccessActions = ['transporter-document-upload'];

    /**
     * Lists all TblTransporter models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblTransporterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblTransporter model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $bsearchModel = new TblBankDetailsSearch();
        $bsearchModel->module_name = 'transporter';
        $bsearchModel->module_code = $id;
        $bdataProvider = $bsearchModel->search(Yii::$app->request->queryParams);
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'transporter';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
        ]);
    }

    /**
     * Creates a new TblTransporter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblTransporter();
        $this->bankDetails = new TblBankDetails();
        $this->contactDetails = new TblContactDetails();

        $this->viewFile = 'create';
        $validate = 1;
        $master = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->transporter_code = $this->model->getCode();
            $this->model->transporter_name = ucwords($this->model->transporter_name);

            $this->bankDetails->load(Yii::$app->request->post());
            if (!empty($this->bankDetails->bank_code)) {
                $this->bankDetails->setModel('transporter', $this->model->transporter_code);
                $this->bankDetails->scenario = 'bank_selected';
                $master[] = $this->bankDetails;
            }
            $this->contactDetails->load(Yii::$app->request->post());
            $this->contactDetails->setModel('transporter', $this->model->transporter_code);
            $master[] = $this->contactDetails;
//            var_dump($_POST);exit;
            $this->model->agreement_from_date = !empty($this->model->agreement_from_date) ? Yii::$app->formatter->asDate($this->model->agreement_from_date, DATE_FORMAT) : '';
            $this->model->agreement_to_date = !empty($this->model->agreement_to_date) ? Yii::$app->formatter->asDate($this->model->agreement_to_date, DATE_FORMAT) : '';
            $master[] = $this->model;
            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique($this->model, 'transporter_name', $this->model->transporter_name);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction($master, ['Transporter', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblTransporter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;

        if (Yii::$app->request->post()) {

            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'transporter_name', $_POST['TblTransporter']['transporter_name']);
            if ($validate == 1) {
                $historyModel = new TblTransporterHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);

                $this->model->load(Yii::$app->request->post());
                $this->model->transporter_name = ucwords($this->model->transporter_name);
                $this->model->agreement_from_date = !empty($this->model->agreement_from_date) ? Yii::$app->formatter->asDate($this->model->agreement_from_date, DATE_FORMAT) : '';
                $this->model->agreement_to_date = !empty($this->model->agreement_to_date) ? Yii::$app->formatter->asDate($this->model->agreement_to_date, DATE_FORMAT) : '';
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Transporter', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblTransporter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_transporter', Yii::$app->request->post('id'), 'transporter_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblTransporterHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->transporter_code]);
    }

    /**
     * Finds the TblTransporter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblTransporter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblTransporter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBankDetails($id) {
        $bankDetails = new TblBankDetails();
        $bankDetails->scenario = 'additional';
        $searchModel = new TblBankDetailsSearch();
        $searchModel->module_name = 'transporter';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modelTransp = $this->findModel($id);
        return $this->render('../../../details/views/tbl-bank-details/create', [
                    'model' => $bankDetails,
                    'id' => $id,
                    'module' => 'transporter',
                    'dist' => $modelTransp->district_code,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dist_field' => 'tbltransporter-district_code'
        ]);
    }

    public function actionContactDetails($id) {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'transporter';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'transporter',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
                    'bankDetails' => $this->bankDetails,
                    'contactDetails' => $this->contactDetails
        ]);
    }

    public function actionTransporterActivate($id) {
        $this->model = $this->findModel($id);
        $HistoryModel = new TblTransporterHistory();
        Yii::$app->operation->history($this->model, $HistoryModel, UPDATE);
        $this->model->scenario = 'activation';
        $this->model->is_active = 1;
        $transaction = $this->generalModel->saveTransaction([$this->model, $HistoryModel], ['Transporter', 'edit']);
        if ($transaction == 'customRedirect') {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'Transporter Activated successfully.']);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Could not Activate. Please try again.']);
        }
        return $this->redirect(['index']);
    }

    public function actionTransporterDeactivate($id) {
        $this->model = $this->findModel($id);
        $HistoryModel = new TblTransporterHistory();
        Yii::$app->operation->history($this->model, $HistoryModel, UPDATE);
        $this->model->scenario = 'activation';
        $this->model->is_active = 0;
        $transaction = $this->generalModel->saveTransaction([$this->model, $HistoryModel], ['Transporter', 'edit']);
        if ($transaction == 'customRedirect') {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'Transporter Deactivated Successfully.']);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Could not Deactivated. Please try again.']);
        }
        return $this->redirect(['index']);
    }

    public function actionTransporterDocumentUpload($id) {
        $model = $this->findModel($id);
        $module_code = $model->transporter_code;
        $module_name = 'tbl_transporter';
        $val = new TblAttachmentController($this->id, $this->module);
        return $val->actiondocumentUpload('transporter', $id, $model, $module_code, $module_name);
    }

}
