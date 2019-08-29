<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblDcsMilkDispatch;
use app\modules\collection\models\TblDcsMilkDispatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblDcsMilkDispatchTxnSearch;
/**
 * TblDcsMilkDispatchController implements the CRUD actions for TblDcsMilkDispatch model.
 */
class TblDcsMilkDispatchController extends \app\controllers\ChildController {

    /**
     * Lists all TblDcsMilkDispatch models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDcsMilkDispatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDcsMilkDispatch model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $bsearchModel = new TblDcsMilkDispatchTxnSearch();
        $bsearchModel->dcs_milk_dispatch_code = $id;
        $bdataProvider = $bsearchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
        ]);
    }

    /**
     * Creates a new TblDcsMilkDispatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblDcsMilkDispatch();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->dcs_milk_dispatch_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblDcsMilkDispatch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->dcs_milk_dispatch_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblDcsMilkDispatch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblDcsMilkDispatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDcsMilkDispatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsMilkDispatch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
