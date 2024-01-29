<?php

namespace app\modules\feedback\controllers;

use Yii;
use app\modules\feedback\models\TblEiplAppFeedbackMasterTxn;
use app\modules\feedback\models\TblEiplAppFeedbackMasterTxnSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblEiplAppFeedbackMasterTxnController implements the CRUD actions for TblEiplAppFeedbackMasterTxn model.
 */
class TblEiplAppFeedbackMasterTxnController extends \app\controllers\ChildController {

    /**
     * Lists all TblEiplAppFeedbackMasterTxn models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblEiplAppFeedbackMasterTxnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblEiplAppFeedbackMasterTxn model.
     * @param integer $eipl_app_feedback_master_txn_code
     * @return mixed
     */
    public function actionView($eipl_app_feedback_master_txn_code) {
        return $this->render('view', [
                    'model' => $this->findModel($eipl_app_feedback_master_txn_code),
        ]);
    }

    /**
     * Creates a new TblEiplAppFeedbackMasterTxn model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblEiplAppFeedbackMasterTxn();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'eipl_app_feedback_master_txn_code' => $model->eipl_app_feedback_master_txn_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblEiplAppFeedbackMasterTxn model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $eipl_app_feedback_master_txn_code
     * @return mixed
     */
    public function actionUpdate($eipl_app_feedback_master_txn_code) {
        $model = $this->findModel($eipl_app_feedback_master_txn_code);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'eipl_app_feedback_master_txn_code' => $model->eipl_app_feedback_master_txn_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblEiplAppFeedbackMasterTxn model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $eipl_app_feedback_master_txn_code
     * @return mixed
     */
    public function actionDelete($eipl_app_feedback_master_txn_code) {
        $this->findModel($eipl_app_feedback_master_txn_code)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblEiplAppFeedbackMasterTxn model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblEiplAppFeedbackMasterTxn the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblEiplAppFeedbackMasterTxn::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
