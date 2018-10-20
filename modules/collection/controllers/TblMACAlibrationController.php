<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMACAlibration;
use app\modules\collection\models\TblMACAlibrationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblMACAlibrationController implements the CRUD actions for TblMACAlibration model.
 */
class TblMACAlibrationController extends \app\controllers\ChildController {

    /**
     * Lists all TblMACAlibration models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMACAlibrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMACAlibration model.
     * @param string $BMCCode
     * @param string $dtdate
     * @param string $MilkType
     * @param string $PPCode
     * @param string $shift
     * @return mixed
     */
    public function actionView($BMCCode, $dtdate, $MilkType, $PPCode, $shift) {
        return $this->render('view', [
                    'model' => $this->findModel($BMCCode, $dtdate, $MilkType, $PPCode, $shift),
        ]);
    }

    /**
     * Creates a new TblMACAlibration model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMACAlibration();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'BMCCode' => $model->BMCCode, 'dtdate' => $model->dtdate, 'MilkType' => $model->MilkType, 'PPCode' => $model->PPCode, 'shift' => $model->shift]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMACAlibration model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $BMCCode
     * @param string $dtdate
     * @param string $MilkType
     * @param string $PPCode
     * @param string $shift
     * @return mixed
     */
    public function actionUpdate($BMCCode, $dtdate, $MilkType, $PPCode, $shift) {
        $model = $this->findModel($BMCCode, $dtdate, $MilkType, $PPCode, $shift);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'BMCCode' => $model->BMCCode, 'dtdate' => $model->dtdate, 'MilkType' => $model->MilkType, 'PPCode' => $model->PPCode, 'shift' => $model->shift]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMACAlibration model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $BMCCode
     * @param string $dtdate
     * @param string $MilkType
     * @param string $PPCode
     * @param string $shift
     * @return mixed
     */
    public function actionDelete($BMCCode, $dtdate, $MilkType, $PPCode, $shift) {
        $this->findModel($BMCCode, $dtdate, $MilkType, $PPCode, $shift)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMACAlibration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $BMCCode
     * @param string $dtdate
     * @param string $MilkType
     * @param string $PPCode
     * @param string $shift
     * @return TblMACAlibration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($BMCCode, $dtdate, $MilkType, $PPCode, $shift) {
        if (($model = TblMACAlibration::findOne(['BMCCode' => $BMCCode, 'dtdate' => $dtdate, 'MilkType' => $MilkType, 'PPCode' => $PPCode, 'shift' => $shift])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
