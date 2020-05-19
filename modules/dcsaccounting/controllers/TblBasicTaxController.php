<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblBasicTax;
use app\modules\dcsaccounting\models\TblBasicTaxHistory;
use app\modules\dcsaccounting\models\TblBasicTaxSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblBasicTaxController implements the CRUD actions for TblBasicTax model.
 */
class TblBasicTaxController extends \app\controllers\ChildController {

    /**
     * Lists all TblBasicTax models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBasicTaxSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblBasicTax model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBasicTax();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->basic_tax_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->basic_tax_name = ucwords($this->model->basic_tax_name);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Basic Tax', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }

        return $this->customRender();
    }

    /**
     * Updates an existing TblBasicTax model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {

            $historyModel = new TblBasicTaxHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);

            $this->model->load(Yii::$app->request->post());
            $this->model->basic_tax_name = ucfirst($this->model->basic_tax_name);

            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Basic Tax', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblBasicTax model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblBasicTaxHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblBasicTax model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBasicTax the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBasicTax::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
