<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblBmcMilkDispatch;
use app\modules\tankermovement\models\TblBmcMilkDispatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxn;
use app\modules\configuration\models\TblConfigMapping;
use app\modules\tankermovement\models\TblConfigTxnResult;

/**
 * TblBmcMilkDispatchController implements the CRUD actions for TblBmcMilkDispatch model.
 */
class TblBmcMilkDispatchController extends \app\controllers\ChildController {

    /**
     * Lists all TblBmcMilkDispatch models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcMilkDispatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBmcMilkDispatch model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBmcMilkDispatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblBmcMilkDispatch();
        $transaction = new TblBmcMilkDispatchTxn();
        if ($model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $transaction = $this->generalModel->saveTransaction([], ['BMC Milk Dispatch', 'create']);
        }
        return $this->render('create', [
                    'model' => $model,
                    'transaction' => $transaction,
        ]);
    }

    /**
     * Updates an existing TblBmcMilkDispatch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bmc_milk_dispatch_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblBmcMilkDispatch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblBmcMilkDispatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBmcMilkDispatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcMilkDispatch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
