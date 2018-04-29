<?php

namespace app\modules\bipl\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\bipl\models\BiplChangeAcknowledgement;
use app\modules\bipl\models\BiplChangeAcknowledgementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BiplChangeAcknowledgementController implements the CRUD actions for BiplChangeAcknowledgement model.
 */
class BiplChangeAcknowledgementController extends ChildController
{

    /**
     * Lists all BiplChangeAcknowledgement models.
     * @return mixed
     */
    public function actionIndex($flag)
    {
        $searchModel = new BiplChangeAcknowledgementSearch();
        if($flag=='rate')
        {
            $searchModel->svc='save_analyzer_rate_logs';
        }
        else
        {
            $searchModel->svc='save_analyzer_vendor_logs';
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BiplChangeAcknowledgement model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BiplChangeAcknowledgement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BiplChangeAcknowledgement();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BiplChangeAcknowledgement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BiplChangeAcknowledgement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BiplChangeAcknowledgement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BiplChangeAcknowledgement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BiplChangeAcknowledgement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
