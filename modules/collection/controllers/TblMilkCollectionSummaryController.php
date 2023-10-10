<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkCollectionSummary;
use app\modules\collection\models\TblMilkCollectionSummarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblMilkCollectionSummaryHistory;

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
        $this->model = new TblMilkCollectionSummary();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->milk_collection_summary_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->date_time_of_collection = ($this->model->date_time_of_collection) ? Yii::$app->formatter->asDate($this->model->date_time_of_collection, DATE_FORMAT) : '';
            $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $this->model->data_inserted_from = 'PORTAL';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Milk Collection Summary', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblMilkCollectionSummary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblMilkCollectionSummaryHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Milk Collection Summary', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
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
