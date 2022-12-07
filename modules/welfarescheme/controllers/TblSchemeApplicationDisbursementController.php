<?php

namespace app\modules\welfarescheme\controllers;

use Yii;
use app\modules\welfarescheme\models\TblSchemeApplicationDisbursement;
use app\modules\welfarescheme\models\TblSchemeApplicationDisbursementSearch;
use app\modules\welfarescheme\models\TblSchemeApplicationDisbursementHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblSchemeApplicationDisbursementController implements the CRUD actions for TblSchemeApplicationDisbursement model.
 */
class TblSchemeApplicationDisbursementController extends \app\controllers\ChildController {

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
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->disburse_date = !empty($this->model->disburse_date) ? date('Y-m-d', strtotime($this->model->disburse_date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Scheme Application Disbursement', 'create']);
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
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

}
