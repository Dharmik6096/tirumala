<?php

namespace app\modules\welfarescheme\controllers;

use Yii;
use app\modules\welfarescheme\models\TblSchemeApplicationDisbursement;
use app\modules\welfarescheme\models\TblSchemeApplicationDisbursementSearch;
use app\modules\welfarescheme\models\TblSchemeApplicationDisbursementHistory;
use app\modules\welfarescheme\models\TblSchemeApplication;
use app\modules\welfarescheme\models\TblSchemeApplicationHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * TblSchemeApplicationDisbursementController implements the CRUD actions for TblSchemeApplicationDisbursement model.
 */
class TblSchemeApplicationDisbursementController extends \app\controllers\ChildController {

    public $freeAccessActions = ['bank-detail'];

    /**
     * Lists all TblSchemeApplicationDisbursement models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSchemeApplicationDisbursementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSchemeApplicationDisbursement model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblSchemeApplicationDisbursement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblSchemeApplicationDisbursement();
        $this->viewFile = 'create';
        $master = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $app_scheme = TblSchemeApplication::findOne(['application_id' => $this->model->application_id, 'application_status' => 'approved']);

            $this->model->disburse_date = !empty($this->model->disburse_date) ? date('Y-m-d', strtotime($this->model->disburse_date)) : '';
            $this->model->disburse_by = \Yii::$app->user->identity->user_code;
            $this->model->disburse_value = $app_scheme->approved_value;
            $master[] = $this->model;

            $historyModel = new TblSchemeApplicationHistory();
            Yii::$app->operation->history($app_scheme, $historyModel, 'Update');
            $master[] = $historyModel;

            $app_scheme->application_status = 'disbursed';
            $app_scheme->status_date = $this->model->disburse_date;
            $app_scheme->status_by = \Yii::$app->user->identity->user_code;
            $app_scheme->status_remarks = $this->model->remarks;
            $master[] = $app_scheme;

            $transaction = $this->generalModel->saveTransaction($master, ['Scheme Application Disbursement', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblSchemeApplicationDisbursement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblSchemeApplicationDisbursementHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->disburse_date = !empty($this->model->disburse_date) ? date('Y-m-d', strtotime($this->model->disburse_date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Scheme Application Disbursement', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render($this->viewFile, ['model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblSchemeApplicationDisbursement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblSchemeApplicationDisbursementHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblSchemeApplicationDisbursement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSchemeApplicationDisbursement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSchemeApplicationDisbursement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBankDetail() {
        $status = 'error';
        $data = [];
        $app_id = !empty($_POST['id']) ? $_POST['id'] : NULL;
        if (!empty($app_id) && is_numeric($app_id)) {
            $application = TblSchemeApplication::findOne($app_id);
            if (!empty($application)) {
                $scheme_detail = $application->schemeId;
                if (!empty($scheme_detail) && $scheme_detail->applicable_for == 'self') {
                    if ($application->customer_type == 'MEMBER') {
                        $bank_detail = $application->memberCode;
                    } else if ($application->customer_type == 'DCS') {
                        $dcs = $application->dcsCode;
                        $bank_detail = !empty($dcs) ? $dcs->defaultBankDetail : NULL;
                    } else {
                        $customer = $application->mainCustomerCode;
                        $bank_detail = !empty($customer) ? $customer->defaultBankDetail : NULL;
                    }
                    if (!empty($bank_detail)) {
                        $status = 'success';
                        $data['bank_code'] = $bank_detail->bank_code;
                        $data['branch_code'] = $bank_detail->branch_code;
                        $data['ifsc'] = $bank_detail->ifsc;
                        $data['bank_account_no'] = $bank_detail->bank_account_no;
                        $data['beneficiary_name'] = $bank_detail->beneficiary_name;
                    }
                }
            }
        }
        return Json::encode(['status' => $status, 'data' => $data]);
    }

}
