<?php

namespace app\modules\product\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\product\models\TblGeneralPartyMaster;
use app\modules\product\models\TblGeneralPartyMasterHistory;
use app\modules\product\models\TblGeneralPartyMasterSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;


/**
 * TblGeneralPartyMasterController implements the CRUD actions for TblGeneralPartyMaster model.
 */
class TblGeneralPartyMasterController extends ChildController
{
    /**
     * Lists all TblGeneralPartyMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblGeneralPartyMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblGeneralPartyMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     public function actionCreate() {
        $this->model = new TblGeneralPartyMaster();
        $this->viewFile = 'create';
        $this->model->party_type = 'EMPLOYEE';
        if ($this->model->load(Yii::$app->request->post())) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['General Party', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblGeneralPartyMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblGeneralPartyMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['General Party', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }


    /**
     * Deletes an existing TblGeneralPartyMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblGeneralPartyMasterHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }


    /**
     * Finds the TblGeneralPartyMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblGeneralPartyMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblGeneralPartyMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
