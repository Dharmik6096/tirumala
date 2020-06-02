<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductDispatchTransaction;
use app\modules\product\models\TblProductDispatchTransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblProductDispatchTransactionHistory;

/**
 * TblProductDispatchTransactionController implements the CRUD actions for TblProductDispatchTransaction model.
 */
class TblProductDispatchTransactionController extends \app\controllers\ChildController {

    /**
     * Lists all TblProductDispatchTransaction models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProductDispatchTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductDispatchTransaction model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblProductDispatchTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblProductDispatchTransaction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->dispatch_transaction_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblProductDispatchTransaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->scenario = 'dispatchUpdate';
        if (Yii::$app->request->post()) {
            $historyModel = new TblProductDispatchTransactionHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->scenario = 'dispatchUpdate';
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['product material dispatch transaction', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->redirect(['/product/tbl-product-dispatch-transaction/view', 'id' => $this->model->dispatch_transaction_code]);
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblProductDispatchTransaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblProductDispatchTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblProductDispatchTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductDispatchTransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
