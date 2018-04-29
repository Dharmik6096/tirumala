<?php

namespace app\modules\globalmaster\controllers;

use Yii;
use app\modules\globalmaster\models\TblCasteCategory;
use app\modules\globalmaster\models\TblCasteCategorySearch;
use app\modules\globalmaster\models\TblCasteCategoryHistory;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblCasteCategoryController implements the CRUD actions for TblCasteCategory model.
 */
class TblCasteCategoryController extends ChildController {
    /**
     * @inheritdoc
     */

    /**
     * Lists all TblCasteCategory models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblCasteCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * Creates a new TblCasteCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblCasteCategory();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->caste_category_name = ucwords($this->model->caste_category_name);
            $this->model->caste_category_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Caste Category', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblCasteCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblCasteCategoryHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->caste_category_name = ucwords($this->model->caste_category_name);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Caste Category', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblCasteCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {

        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_caste_category', Yii::$app->request->post('id'), 'caste_category_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblCasteCategoryHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblCasteCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblCasteCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCasteCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
