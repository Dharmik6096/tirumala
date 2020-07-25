<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblFormulaMaster;
use app\modules\dcsoperation\models\TblFormulaMasterSearch;
use app\modules\dcsoperation\models\TblFormulaHistory;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * FormulaMasterController implements the CRUD actions for TblFormulaMaster model.
 */
class FormulaMasterController extends \app\controllers\ChildController {

    /**
     * Lists all TblFormulaMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblFormulaMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblFormulaMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblFormulaMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblFormulaMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {

            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);

            // $rows = $this->model->getSameTypeData();
            $rows['formula_code'] = 0;
            if ($rows['formula_code'] > 0) {

                $this->model->formula_code = $this->model->getCode();
                $this->model->is_active = 1;
                $rows['is_active'] = 0;

                $historyModel = new TblFormulaHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $transaction = $this->generalModel->saveTransaction([$this->model, $rows, $historyModel], ['Milk Rate formula', 'create']);

                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            } else {

                $this->model->formula_code = $this->model->getCode();
                $this->model->is_active = 1;
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Milk Rate formula', 'create']);

                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }

        return $this->customRender();
    }

    /**
     * Updates an existing TblFormulaMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {

            $historyModel = new TblFormulaHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['formula', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblFormulaMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_formula', Yii::$app->request->post('id'), 'formula_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblFormulaHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblFormulaMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblFormulaMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblFormulaMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetFormula() {

        $formula = [];
        $status = 'error';
        if (!empty($_POST['wefDate']) && !empty($_POST['milkType']) && !empty($_POST['rateType'])) {

            $model = new TblFormulaMaster();
            $model->milk_type_code = Yii::$app->request->post('milkType');
            $model->rate_type_code = Yii::$app->request->post('rateType');
            $model->union_code = Yii::$app->request->post('union_code');
            $model->wef_date = Yii::$app->request->post('wefDate');
            $dropdwon = !empty(Yii::$app->request->post('dropdown')) ? TRUE : FALSE;
            $formula = $model->getPurchaseRateFormula($dropdwon);

            $status = !empty($formula) ? 'success' : 'error';
        }
        echo \yii\helpers\Json::encode(['status' => $status, 'data' => $formula]);
    }

    public function actionCreateTextFormula() {
        $this->model = new TblFormulaMaster();
        $this->viewFile = 'create_text_rate';
        $this->model->scenario = 'TextFormula';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $this->model->formula_code = $this->model->getCode();
            $this->model->formula_description = $this->model->formula;
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Milk Rate formula', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

}
