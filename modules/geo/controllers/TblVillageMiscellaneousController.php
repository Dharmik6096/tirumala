<?php

namespace app\modules\geo\controllers;

use Yii;
use app\modules\geo\models\TblVillageMiscellaneous;
use app\modules\geo\models\TblVillageMiscellaneousSearch;
use app\modules\geo\models\TblVillageMiscellaneousHistory;
use app\modules\globalmaster\models\TblMiscellaneous;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\Url;

class TblVillageMiscellaneousController extends ChildController {

    /**
     * Lists all TblVillageMiscellaneous models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVillageMiscellaneousSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblVillageMiscellaneous model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->viewFile = 'create';
        $this->model = new TblVillageMiscellaneous();
        if ($this->model->load(Yii::$app->request->post())) {            
            $this->model->village_miscellaneous_code = $this->model->getCode();
            $this->model->village_code = Yii::$app->getRequest()->getQueryParam('id');

            $transaction = $this->generalModel->saveTransaction([$this->model], ['village miscellaneous', 'create']);

            if ($transaction !== FALSE) {
                return $this->$transaction();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVillageMiscellaneous model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblVillageMiscellaneousHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->description = ucwords($this->model->description);

            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['village miscellaneous', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblVillageMiscellaneous model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_village_miscellaneous',Yii::$app->request->post('id'), 'village_miscellaneous_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblVillageMiscellaneousHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblVillageMiscellaneous model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVillageMiscellaneous the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVillageMiscellaneous::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function customRedirect() {
        return $this->redirect(Url::previous());
    }

    protected function customRender() {
        $miscellaneousModel = new TblMiscellaneous();
        $miscellaneousData = $miscellaneousModel->getActiveMiscellaneous();
        return $this->render($this->viewFile, ['model' => $this->model, 'miscellaneous' => $miscellaneousData]);
    }
}
