<?php

namespace app\modules\geo\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblStatesSearch;
use app\modules\geo\models\TblStatesHistory;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblStatesController implements the CRUD actions for TblStates model.
 */
class TblStatesController extends ChildController {

    public function actionIndex() {
        $searchModel = new TblStatesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider]);
    }

    /**
     * Creates a new TblStates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblStates();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->state_name = ucwords($this->model->state_name);

            $transaction = $this->generalModel->saveTransaction([$this->model], ['state', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblStates model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblStatesHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->state_name = ucwords($this->model->state_name);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['state', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblStates model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_states', Yii::$app->request->post('id'), 'state_code']);
        echo $valueOut;exit;
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblStatesHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblStates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblStates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblStates::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }    
}
