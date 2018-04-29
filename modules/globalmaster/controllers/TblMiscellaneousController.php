<?php

namespace app\modules\globalmaster\controllers;

use Yii;
use app\modules\globalmaster\models\TblMiscellaneous;
use app\modules\globalmaster\models\TblMiscellaneousSearch;
use app\modules\globalmaster\models\TblMiscellaneousHistory;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblMiscellaneousController implements the CRUD actions for TblMiscellaneous model.
 */
class TblMiscellaneousController extends ChildController {
    /**
     * @inheritdoc
     */

    /**
     * Lists all TblMiscellaneous models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMiscellaneousSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblMiscellaneous model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->viewFile = 'create';
        $this->model = new TblMiscellaneous();


        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->miscellaneous_name = ucwords($this->model->miscellaneous_name);
            $this->model->miscellaneous_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['miscellaneous', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblMiscellaneous model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->viewFile = 'update';
        $this->model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $historyModel = new TblMiscellaneousHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
           $this->model->miscellaneous_name = ucwords($this->model->miscellaneous_name);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['miscellaneous', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblMiscellaneous model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_miscellaneous', Yii::$app->request->post('id'), 'miscellaneous_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblMiscellaneousHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblMiscellaneous model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMiscellaneous the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMiscellaneous::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
