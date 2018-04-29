<?php

namespace app\modules\reil\controllers;

use Yii;
use app\modules\reil\models\PoolingPoint;
use app\modules\reil\models\PoolingPointSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PoolingPointController implements the CRUD actions for PoolingPoint model.
 */
class PoolingPointController extends \app\controllers\ChildController
{
    /**
     * Lists all PoolingPoint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PoolingPointSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PoolingPoint model.
     * @param string $BMCCode
     * @param string $PlantCode
     * @param string $PPCode
     * @return mixed
     */
    public function actionView($BMCCode, $PlantCode, $PPCode)
    {
        return $this->render('view', [
            'model' => $this->findModel($BMCCode, $PlantCode, $PPCode),
        ]);
    }

    /**
     * Creates a new PoolingPoint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PoolingPoint();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'BMCCode' => $model->BMCCode, 'PlantCode' => $model->PlantCode, 'PPCode' => $model->PPCode]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PoolingPoint model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $BMCCode
     * @param string $PlantCode
     * @param string $PPCode
     * @return mixed
     */
    public function actionUpdate($BMCCode, $PlantCode, $PPCode)
    {
        $model = $this->findModel($BMCCode, $PlantCode, $PPCode);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'BMCCode' => $model->BMCCode, 'PlantCode' => $model->PlantCode, 'PPCode' => $model->PPCode]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PoolingPoint model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $BMCCode
     * @param string $PlantCode
     * @param string $PPCode
     * @return mixed
     */
    public function actionDelete($BMCCode, $PlantCode, $PPCode)
    {
        $this->findModel($BMCCode, $PlantCode, $PPCode)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PoolingPoint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $BMCCode
     * @param string $PlantCode
     * @param string $PPCode
     * @return PoolingPoint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($BMCCode, $PlantCode, $PPCode)
    {
        if (($model = PoolingPoint::findOne(['BMCCode' => $BMCCode, 'PlantCode' => $PlantCode, 'PPCode' => $PPCode])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

