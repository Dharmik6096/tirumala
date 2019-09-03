<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkCollectionSummary;
use app\modules\collection\models\TblMilkCollectionSummarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblMilkCollectionSummaryController implements the CRUD actions for TblMilkCollectionSummary model.
 */
class TblMilkCollectionSummaryController extends \app\controllers\ChildController {

    /**
     * Lists all TblMilkCollectionSummary models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkCollectionSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMilkCollectionSummary model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMilkCollectionSummary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMilkCollectionSummary();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->milk_collection_summary_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMilkCollectionSummary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->milk_collection_summary_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

   
    /**
     * Finds the TblMilkCollectionSummary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkCollectionSummary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkCollectionSummary::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
