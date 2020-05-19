<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\modules\staffmanagement\models\TblStaffSalary;
use app\modules\staffmanagement\models\TblStaffSalarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\globalmaster\models\TblSalaryHeads;
use app\modules\staffmanagement\models\TblStaffSalaryTransaction;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Json;
use app\modules\staffmanagement\models\TblStaffSalaryTransactionHistory;
use app\modules\staffmanagement\models\TblStaffSalaryHistory;

/**
 * TblStaffSalaryController implements the CRUD actions for TblStaffSalary model.
 */
class TblStaffSalaryController extends \app\controllers\ChildController {

    /**
     * Lists all TblStaffSalary models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblStaffSalarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblStaffSalary model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblStaffSalary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblStaffSalary();
        $master = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $transData = Yii::$app->request->post('TblStaffSalaryTransaction');
            $this->model->staff_salary_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $date = date('01-') . $this->model->wef_date;
            $this->model->wef_date = !empty($date) ? date('Y-m-d', strtotime($date)) : NULL;
            $master[] = $this->model;
            $i = 1;
            foreach ($transData as $key => $value) {
                $salaryTrns = new TblStaffSalaryTransaction();
                $salaryTrns->staff_salary_transaction_code = (string) Yii::$app->general->getCodeAutoIncrement($salaryTrns, $i);
                $salaryTrns->salary_head_code = $key;
                $salaryTrns->staff_salary_code = $this->model->staff_salary_code;
                $salaryTrns->union_code = $this->model->union_code;
                $i++;
                $value['staff_salary_transaction_code'] = $salaryTrns->staff_salary_transaction_code;
                $salaryTrns->setAttributes($value);
                $master[] = $salaryTrns;
            }
            $transaction = $this->generalModel->saveTransaction($master, ['Staff Salary', 'create']);
            if ($transaction == 'customRedirect') {
                $url = Url::to(['index']);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['status' => 'success', 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($this->model);
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblStaffSalary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = new TblStaffSalary();
        $this->model = $model->findOne($id);
        if (Yii::$app->request->post()) {
            $master = [];
            $historyModel = new TblStaffSalaryHistory();
            Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
            $master[] = $historyModel;

            $this->model->load(Yii::$app->request->post());
            $date = date('01-') . $this->model->wef_date;
            $this->model->wef_date = !empty($date) ? date('Y-m-d', strtotime($date)) : NULL;
            $master[] = $this->model;

            $Transaction = Yii::$app->request->post('TblStaffSalaryTransaction');
            $i = 1;
            foreach ($Transaction as $key => $value) {
                $salaryTrns = new TblStaffSalaryTransaction();
                if (!empty($value['staff_salary_transaction_code'])) {
                    $trnsModel = $salaryTrns->findOne($value['staff_salary_transaction_code']);
                    $salaryTrns->staff_salary_transaction_code = $value['staff_salary_transaction_code'];
                    if (!empty($trnsModel)) {
                        $trHistoryModel = new TblStaffSalaryTransactionHistory();
                        Yii::$app->operation->history($trnsModel, $trHistoryModel, 'UPDATE');
                        $master[] = $trHistoryModel;
                        $salaryTrns = $trnsModel;
                    }
                } else {
                    $salaryTrns->staff_salary_transaction_code = (string) Yii::$app->general->getCodeAutoIncrement($salaryTrns, $i);
                    $i++;
                }
                $value['staff_salary_transaction_code'] = $salaryTrns->staff_salary_transaction_code;
                $salaryTrns->salary_head_code = $key;
                $salaryTrns->staff_salary_code = $this->model->staff_salary_code;
                $salaryTrns->setAttributes($value);
                $master[] = $salaryTrns;
            }
            $transaction = $this->generalModel->saveTransaction($master, ['Staff Salary', 'edit']);
           
            if ($transaction == 'customRedirect') {
                $url = Url::to(['index']);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['status' => 'success', 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($this->model);
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblStaffSalary model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblStaffSalary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblStaffSalary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblStaffSalary::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSalaryTransaction() {
        $code = Yii::$app->request->get('staff_member_code');
        $date = date('01-') . Yii::$app->request->get('wef_date');
        $id = Yii::$app->request->get('id');
        $date = date('Y-m-d', strtotime($date));
        $salaryModel = new TblStaffSalary();
        $staffSalary = $salaryModel->getSalaryData($code, $date);
        $type = 'create';
        if (!empty($id)) {
            $type = 'edit';
            $salaryModel = $salaryModel->findOne($id);
        }
        $staffHead = new TblSalaryHeads();
        $staffHeadAdd = $staffHead->getHeadAddition();
        $staffHeadDeduct = $staffHead->getHeadDeduct();
        $transModel = new TblStaffSalaryTransaction();
        return $this->renderAjax('staff_salary_detail_form', [
                    'staffSalary' => $staffSalary,
                    'salaryModel' => $salaryModel,
                    'staffHeadAdd' => $staffHeadAdd,
                    'staffHeadDeduct' => $staffHeadDeduct,
                    'transModel' => $transModel,
                    'type' => $type
        ]);
    }

}
