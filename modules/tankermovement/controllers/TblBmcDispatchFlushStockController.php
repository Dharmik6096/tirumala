<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblBmcDispatchFlushStock;
use app\modules\tankermovement\models\TblBmcDispatchFlushStockSearch;
use yii\web\NotFoundHttpException;
use app\modules\tankermovement\models\TblBmcDispatchFlushStockHistory;

/**
 * TblBmcDispatchFlushStockController implements the CRUD actions for TblBmcDispatchFlushStock model.
 */
class TblBmcDispatchFlushStockController extends \app\controllers\ChildController {

    /**
     * Lists all TblBmcDispatchFlushStock models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcDispatchFlushStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBmcDispatchFlushStock model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBmcDispatchFlushStock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBmcDispatchFlushStock();
        $this->viewFile = 'create';
        Yii::$app->default->getDefaults($this->model);
        Yii::$app->general->setCode($this->model);
        $this->setFromDate($this->model);
        $this->model->scenario = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->x_col1 = Yii::$app->general->getUuid();
            $this->model->transaction_date = ($this->model->transaction_date) ? Yii::$app->formatter->asDate($this->model->transaction_date, DATE_FORMAT) : '';
            $this->model->transaction_date = $this->model->transaction_date . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Flush Qty Punching', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblBmcDispatchFlushStock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->model->scenario = 'update';
        if (Yii::$app->request->post()) {
            $master_model = [];
            $historyModel = new TblBmcDispatchFlushStockHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $master_model[] = $historyModel;
            $this->model->load(Yii::$app->request->post());
            $this->model->transaction_date = ($this->model->transaction_date) ? Yii::$app->formatter->asDate($this->model->transaction_date, DATE_FORMAT) : '';
            $this->model->transaction_date = $this->model->transaction_date . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $master_model[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($master_model, ['Flush Qty Punching', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblBmcDispatchFlushStock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblBmcDispatchFlushStock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBmcDispatchFlushStock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcDispatchFlushStock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function setFromDate($model) {
        $model->transaction_date = date('Y-m-d');
        $currentHour = date('H');
        if ($currentHour >= 0 && $currentHour < 12) {
            $model->shift_code = 1;
        } else {
            $model->shift_code = 2;
        }
    }

}
