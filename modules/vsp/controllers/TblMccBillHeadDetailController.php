<?php

namespace app\modules\vsp\controllers;

use Yii;
use app\modules\vsp\models\TblMccBillHeadDetail;
use app\modules\vsp\models\TblMccBillHeadDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\vsp\models\TblMccBillHeadInstallment;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/**
 * TblMccBillHeadDetailController implements the CRUD actions for TblMccBillHeadDetail model.
 */
class TblMccBillHeadDetailController extends \app\controllers\ChildController {

    /**
     * Lists all TblMccBillHeadDetail models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMccBillHeadDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMccBillHeadDetail model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblMccBillHeadDetailSearch();
        $searchModel->mcc_bill_head_detail_code = $id;
        $dataProvider = $searchModel->installmentsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => $dataProvider, 'searchModel' => $searchModel
        ]);
    }

    /**
     * Creates a new TblMccBillHeadDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMccBillHeadDetail();
        $searchModel = new TblMccBillHeadDetailSearch();
        $searchModel->grid_filter = false;
        $dataProvider = $searchModel->gridsearch(Yii::$app->request->get());
        $this->viewFile = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->mcc_bill_head_detail_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->is_active = 1;
            $no = !empty($this->model->no_installment) ? ($this->model->no_installment) : 1;
            $this->model->transaction_date = !empty($this->model->transaction_date) ? date('Y-m-d', strtotime($this->model->transaction_date)) : '';

            $installment = [];
            if ($this->model->validate()) {
                $cycle = $this->model->payment_cycle_code;
                for ($i = 0; $i < $no; $i++) {
                    $instModel = new TblMccBillHeadInstallment();
                    $instModel->mcc_bill_head_detail_code = $this->model->mcc_bill_head_detail_code;
                    $instModel->mcc_bill_head_code = $this->model->mcc_bill_head_code;
                    $instModel->union_code = $this->model->union_code;
                    $instModel->bmc_code = $this->model->bmc_code;
                    $instModel->installement_cycle = ($i + 1);
                    $this->model->amount = empty($this->model->amount) ? 0 : $this->model->amount;
                    $instModel->installment_amount = floatval($this->model->amount / $no);
                    array_push($installment, $instModel);
                }
                if ($this->model->validate()) {
                    $transaction = $this->generalModel->saveTransaction([$this->model], $installment, ['Bill Head Detail', 'create']);
                    if ($transaction == 'customRedirect') {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'success', 'temp_collection_data' => [], 'msg' => $msg];
                    } else {
                        $msg = Yii::$app->getSession()->getFlash('success')['message'];
                        $record = ['status' => 'error', 'temp_collection_data' => [], 'msg' => $msg];
                    }
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        } else {
            return $this->render('create', [
                        'model' => $this->model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            ]);
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing TblMccBillHeadDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->mcc_bill_head_detail_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMccBillHeadDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMccBillHeadDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMccBillHeadDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMccBillHeadDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListGrid() {
        $searchModel = new TblMccBillHeadDetailSearch();
        $searchModel->grid_filter = false;
        $searchModel->setAttributes(Yii::$app->request->get('TblMccBillHeadDetail'));
        $dataProvider = $searchModel->gridsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

}
