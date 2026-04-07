<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblFinancialYear;
use app\modules\dcsaccounting\models\TblFinancialYearSearch;
use app\modules\dcsaccounting\models\TblFinancialYearHistory;
use yii\web\NotFoundHttpException;

/**
 * TblFinancialYearController implements the CRUD actions for TblFinancialYear model.
 */
class TblFinancialYearController extends \app\controllers\ChildController {

    /**
     * Lists all TblFinancialYear models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblFinancialYearSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblFinancialYear model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblFinancialYear();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->starting_date = date('Y-m-d', strtotime($this->model->starting_date));
            $this->model->ending_date = date('Y-m-d', strtotime($this->model->ending_date));
            $this->model->code = 'FY' . $this->model->code;
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Financial Year', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }

        return $this->customRender();
    }

    /**
     * Updates an existing TblFinancialYear model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post() && $this->model->load(Yii::$app->request->post())) {
            $this->model->starting_date = date('Y-m-d', strtotime($this->model->starting_date));
            $this->model->ending_date = date('Y-m-d', strtotime($this->model->ending_date));
            $historyModel = new TblFinancialYearHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->code = 'FY' . $this->model->code;
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Financial Year', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblFinancialYear model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblFinancialYear the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblFinancialYear::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
