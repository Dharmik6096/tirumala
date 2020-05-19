<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductDispatch;
use app\modules\product\models\TblProductDispatchHistory;
use app\modules\product\models\TblProductDispatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblProductDispatchTransaction;
use app\modules\product\models\TblProductDispatchTransactionHistory;
use app\modules\product\models\TblProductRequisition;
use app\modules\product\models\TblProductRequisitionSearch;
use app\modules\product\models\TblProductRequisitionHistory;
use app\modules\product\models\TblProductRequisitionTransaction;
use app\modules\product\models\TblProductRequisitionTransactionHistory;

/**
 * TblProductDispatchController implements the CRUD actions for TblProductDispatch model.
 */
class TblProductDispatchController extends \app\controllers\ChildController {

    /**
     * Lists all TblProductDispatch models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProductDispatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'title' => 'Product Dispatch with Requisition'
        ]);
    }

    /**
     * Displays a single TblProductDispatch model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblProductDispatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblProductDispatch();
        $this->viewFile = 'create';
        $searchModel = new TblProductRequisitionSearch();
//        $schememodal = new TblProductDispatchTransaction();
        if (Yii::$app->request->queryParams) {
            $searchModel->scenario = 'searchdispatch';
        }
        $dataProvider = $searchModel->searchDispatchRequisition(Yii::$app->request->queryParams);
//        $this->flag = 1;
        $modeltransaction = new TblProductDispatchTransaction();

        return $this->render('create', [
                    'model' => $this->model, 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'modeltransaction' => $modeltransaction
//                    'schememodal' => $schememodal
        ]);
    }

    /**
     * Updates an existing TblProductDispatch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->challan_no]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblProductDispatch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblProductDispatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblProductDispatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductDispatch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
