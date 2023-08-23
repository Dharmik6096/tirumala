<?php

namespace app\modules\vsp\controllers;

use Yii;
use app\modules\vsp\models\TblMccGeneralFormula;
use app\modules\vsp\models\TblMccGeneralFormulaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\vsp\models\TblCriteriaKeywordMapping;
use app\modules\vsp\models\TblMccGeneralFormulaHistory;

/**
 * TblGeneralFormulaController implements the CRUD actions for TblGeneralFormula model.
 */
class TblMccGeneralFormulaController extends \app\controllers\ChildController {

    /**
     * Lists all TblGeneralFormula models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMccGeneralFormulaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblGeneralFormula model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblGeneralFormula model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMccGeneralFormula();
        $this->viewFile = 'create';
        $keyword_model = new TblCriteriaKeywordMapping();
        $keyword_data = $keyword_model->getKeywordData();

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->general_formula_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->formula = strtoupper($this->model->formula);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['General Formula', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'keyword_data' => $keyword_data
        ]);
    }

    /**
     * Updates an existing TblGeneralFormula model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $keyword_model = new TblCriteriaKeywordMapping();
        $keyword_data = $keyword_model->getKeywordData();
        if (Yii::$app->request->post()) {
            $historyModel = new TblMccGeneralFormulaHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->formula = strtoupper($this->model->formula);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['General Formula', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
                    'keyword_data' => $keyword_data
        ]);
    }

    /**
     * Deletes an existing TblGeneralFormula model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblMccGeneralFormulaHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblGeneralFormula model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblGeneralFormula the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMccGeneralFormula::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
