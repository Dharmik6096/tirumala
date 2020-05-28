<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\modules\staffmanagement\models\TblStaffAdditionDeduction;
use app\modules\staffmanagement\models\TblStaffAdditionDeductionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\staffmanagement\models\TblStaffInstallment;
use app\modules\staffmanagement\models\TblStaffInstallmentHistory;
use yii\web\Response;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use app\modules\staffmanagement\models\TblStaffInstallmentSearch;

/**
 * TblStaffAdditionDeductionController implements the CRUD actions for TblStaffAdditionDeduction model.
 */
class TblStaffAdditionDeductionController extends \app\controllers\ChildController {

    /**
     * Lists all TblStaffAdditionDeduction models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblStaffAdditionDeductionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblStaffAdditionDeduction model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblStaffInstallmentSearch();
        $searchModel->staff_addition_deduction_no = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id), 'searchModel' => $searchModel, 'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new TblStaffAdditionDeduction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblStaffAdditionDeduction();
        if ($this->model->load(Yii::$app->request->post())) {
            $master = [];
            $this->model->staff_addition_deduction_no = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->tr_date = !empty($this->model->tr_date) ? date('Y-m-d', strtotime($this->model->tr_date)) : NULL;
            $date = date('01-') . $this->model->app_from_date;
            $this->model->app_from_date = !empty($date) ? date('Y-m-d', strtotime($date)) : NULL;
            $master[] = $this->model;
            for ($i = 1; $i <= $this->model->installment_no; $i++) {
                $instModel = new TblStaffInstallment();
                $instModel->staff_installment_code = (string) Yii::$app->general->getCodeAutoIncrement($instModel, $i);
                $instModel->staff_addition_deduction_no = $this->model->staff_addition_deduction_no;
                $instModel->union_code = $this->model->union_code;
                $instModel->amount = $this->model->amount / $this->model->installment_no;
                $instModel->deduction_date = date('Y-m-d', strtotime($date . +($i - 1) . 'month'));
                $instModel->installment_no = $i;
                $master[] = $instModel;
            }

            $transaction = $this->generalModel->saveTransaction($master, ['Staff Addition Deduction', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblStaffAdditionDeduction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = new TblStaffAdditionDeduction();
        $this->model = $model->findOne($id);

        $staffModel = new TblStaffInstallment();
        $staffModel->staff_addition_deduction_no = $this->model->staff_addition_deduction_no;
        $staffModelData = $staffModel->getStaffData();
        return $this->render('update', [
                    'model' => $this->model,
                    'staffModelData' => $staffModelData,
                    'staffModel' => $staffModel
        ]);
    }

    public function actionSalaryInstallment() {
        $data = Yii::$app->request->post();

        $instlModel = new TblStaffInstallment();
        $instlModel->setAttributes($data['TblStaffInstallment']);
        $instlModel->staff_addition_deduction_no = $data['TblStaffAdditionDeduction']['staff_addition_deduction_no'];
        $date = date('01-') . $data['TblStaffInstallment']['deduction_date'];
        $instlModel->deduction_date = $date;
        $instlModel->union_code = $data['TblStaffAdditionDeduction']['union_code'];
        $instlModel->installment_no = $data['TblStaffAdditionDeduction']['installment_no'];
        $result = 'error';
        if ($instlModel->validate()) {
            $result = 'success';
            return $this->renderAjax('salary_install_grid_bind', [
                        'instlModel' => $instlModel,
            ]);
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $data = ActiveForm::validate($instlModel);
            return ['status' => $result, 'error_data' => $data];
        }
    }

    public function actionUpdateSalaryInstallment($id) {
        $model = new TblStaffInstallment();
        if (Yii::$app->request->post()) {
            $master = [];
            $instalData = Yii::$app->request->post('TblStaffInstallment');

            $i = 1;
            foreach ($instalData as $key => $value) {
                $salaryInstal = new TblStaffInstallment();
                if (!empty($value['staff_installment_code'])) {
                    $instlModel = $salaryInstal->findOne($value['staff_installment_code']);
                    $salaryInstal->staff_installment_code = $value['staff_installment_code'];
                    if (!empty($instlModel)) {
                        $trHistoryModel = new TblStaffInstallmentHistory();
                        Yii::$app->operation->history($instlModel, $trHistoryModel, UPDATE);
                        $master[] = $trHistoryModel;
                        $salaryInstal = $instlModel;
                    }
                } else {
                    $salaryInstal->staff_installment_code = (string) Yii::$app->general->getCodeAutoIncrement($salaryInstal, $i);
                    $salaryInstal->installment_no = 0;
                    $i++;
                }
                $salaryInstal->union_code = $value['union_code'];
                $salaryInstal->amount = $value['amount'];
                $salaryInstal->staff_addition_deduction_no = $value['staff_addition_deduction_no'];
                $date = date('01-') . $value['deduction_date'];
                $salaryInstal->deduction_date = !empty($date) ? date('Y-m-d', strtotime($date)) : NULL;
                $master[] = $salaryInstal;
            }

            $transaction = $this->generalModel->saveTransaction($master, ['Salary Installment', 'edit']);
            $result = 'error';
            if ($transaction == 'customRedirect') {
                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = Url::to(['index']);
                return ['status' => $result, 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        return $this->render('salary_add_deduct_update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TblStaffAdditionDeduction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblStaffAdditionDeduction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblStaffAdditionDeduction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblStaffAdditionDeduction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
