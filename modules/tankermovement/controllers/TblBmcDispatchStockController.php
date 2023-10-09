<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblBmcDispatchStock;
use app\modules\tankermovement\models\TblBmcDispatchStockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use kartik\form\ActiveForm;

/**
 * TblBmcDispatchStockController implements the CRUD actions for TblBmcDispatchStock model.
 */
class TblBmcDispatchStockController extends \app\controllers\ChildController {

    /**
     * Lists all TblBmcDispatchStock models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcDispatchStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBmcDispatchStock model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBmcDispatchStock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
//    public function actionCreate() {
//        $model = new TblBmcDispatchStock();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->bmc_dispatch_stock_code]);
//        } else {
//            return $this->render('create', [
//                        'model' => $model,
//            ]);
//        }
//    }

    public function actionCreate() {
        $this->model = new TblBmcDispatchStock();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->bmc_dispatch_stock_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->to_date = ($this->model->to_date) ? Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) : '';
            $this->model->to_date = $this->model->to_date . ' ' . \Yii::$app->general->getshift($this->model->to_shift_code);
            $this->model->type = 'physical';
            $this->model->closing_bal = 0;
            $this->model->scenario = 'create';

            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['BMC Dispatch Stock', 'create']);
                if ($transaction == 'customRedirect') {
                    $searchModel = new TblBmcDispatchStockSearch();
                    $searchModel->setAttributes(Yii::$app->request->get('TblBmcDispatchStock'));
                    $searchModel->union_code = $this->model->union_code;
                    $searchModel->plant_code = $this->model->plant_code;
                    $searchModel->mcc_plant_code = $this->model->mcc_plant_code;
                    $searchModel->bmc_code = $this->model->bmc_code;
                    $searchModel->to_date = $this->model->to_date;
                    $searchModel->to_shift_code = $this->model->to_shift_code;
                    $dataProvider = $searchModel->searchBmcStockDetail([$searchModel->union_code, $searchModel->plant_code, $searchModel->mcc_plant_code, $searchModel->bmc_code, $searchModel->to_date, $searchModel->to_shift_code]);
                    $dataHtml = $this->renderAjax('_transaction_detail', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]);
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'data' => $dataHtml];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    public function actionTransactionDetail() {
        $searchModel = new TblBmcDispatchStockSearch();
        $dataProvider = $searchModel->searchBmcStockDetail(Yii::$app->request->queryParams);
        return $this->renderAjax('_transaction_detail', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListGrid() {
        $searchModel = new TblBmcDispatchStockSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblBmcCollection'));
        $dataProvider = $searchModel->actionTransactionDetail([]);
        return $this->renderAjax('_transaction_detail', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Updates an existing TblBmcDispatchStock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bmc_dispatch_stock_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblBmcDispatchStock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblBmcDispatchStock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBmcDispatchStock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcDispatchStock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
